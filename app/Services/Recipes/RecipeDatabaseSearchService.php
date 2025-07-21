<?php

namespace App\Services\Recipes;

use App\Models\Recipe;
use App\Http\Requests\Recipes\RecipeSearchRequest;
use App\Http\Resources\Recipes\RecipeListCollectionResource;
use App\Http\Resources\Recipes\RecipeDisplayResource;

/**
 * Implements searching and retrieving Recipes from the configured database
 */
class RecipeDatabaseSearchService implements RecipeSearchInterface
{
    /**
     * Retrieve a list of recipes limited by the optional search parameters
     * in the request.
     * 
     * Allow searching for recipes by:
     * - Author email - exact match
     * - Keyword - should match ANY of these fields: name, description, ingredients, or steps
     * - Ingredient - this could be a partial match; for example, “potato” should match “3 large potatoes” in the ingredients list
     * - Allow for combinations of search parameters, which will be queried as AND conditions
     */
    public function getListFromRequest(RecipeSearchRequest $request): RecipeListCollectionResource
    {
        // Eager load the author relations and initialize the query
        $dbRequest = Recipe::query()->with('author');

        // Filter by author email (exact match)
        if ($request->filled('email')) {
            $dbRequest
                ->join('users', 'recipes.user_id', '=', 'users.id')
                ->where('users.email', $request->input('email'));
        }

        // Filter by ingredient (searches ingredients only)
        if ($request->filled('ingredient')) {
            $dbRequest->whereHas('ingredients', function($query) use($request) {
                $query->where('name', 'like', '%'.$request->input('ingredient').'%');
            });
        }

        // Filter by search term (will search ingredient, recipe name, description, or step)
        if ($request->filled('search_term')) {
            $searchTerm = $request->input('search_term');
            // Use a sub-where to contain the logic between (name OR desc OR ingredient OR step)
            $dbRequest->where(function($query) use ($searchTerm){
                $query->where(function($query) use ($searchTerm) {
                    $query
                        ->where('recipes.name', 'like', '%'.$searchTerm.'%')
                        ->orWhere('description', 'like', '%'.$searchTerm.'%');
                })
                ->orWhereHas('ingredients', function($query) use ($searchTerm) {
                    $query->where('ingredients.name', 'like', '%'.$searchTerm.'%');
                })
                ->orWhereHas('steps', function($query) use($searchTerm) {
                    $query->where('step_description', 'like', '%'.$searchTerm.'%');
                });
            });
        }

        return new RecipeListCollectionResource($dbRequest->paginate());
    }

    /**
     * Retrieve a single recipe from the database by slug
     */
    public function getBySlug(string $slug): RecipeDisplayResource
    {
        return new RecipeDisplayResource(Recipe::where('slug', $slug)->firstOrFail());
    }
}