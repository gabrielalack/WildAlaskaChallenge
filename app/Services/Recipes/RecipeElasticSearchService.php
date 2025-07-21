<?php

namespace App\Services\Recipes;

use App\Models\Recipe;
use App\Http\Requests\Recipes\RecipeSearchRequest;
use App\Http\Resources\Recipes\RecipeListCollectionResource;
use App\Http\Resources\Recipes\RecipeDisplayResource;

/**
 * Implements searching and retrieving Recipes leveraging Elastic search
 * 
 * This is here as an example of a future implementation possibility,
 * if our database grows beyond the feasibility of normal database searches
 */
class RecipeElasticSearchService implements RecipeSearchInterface
{
    /**
     * Retrieve a list of recipes limited by the optional search parameters
     * in the request.
     */
    public function getListFromRequest(RecipeSearchRequest $request): RecipeListCollectionResource
    {
        // Todo: implement when needed
    }

    /**
     * Retrieve a single recipe 
     */
    public function getBySlug(string $slug): RecipeDisplayResource
    {
       // Todo: implement when needed
    }
}