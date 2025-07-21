<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Recipes\RecipeListCollectionResource;
use App\Http\Resources\Recipes\RecipeListItemResource;
use App\Http\Resources\Recipes\RecipeDisplayResource;
use App\Http\Requests\Recipes\RecipeSearchRequest;
use App\Services\Recipes\RecipeSearchInterface;


class RecipeResourceController extends Controller
{
    public function __construct(
        protected RecipeSearchInterface $recipeSearchService,
    )
    {}

    /**
     * Display a listing of the resource.
     * 
     * @return RecipeListCollectionResource<RecipeListItemResource>|JsonResponse
     */
    public function index(RecipeSearchRequest $request): RecipeListCollectionResource|JsonResponse
    {
       try {
            return $this->recipeSearchService->getListFromRequest($request);
        } catch (Exception $e) {
            // Log or report error to some tracking service such as sentry
            //  Note: this should all be handled centrally in the framework error handler
            // Log($e);
            // ReportToSentry($e);

            // Respond with a generic 500 json response
            return response()->json(['message' => 'Application Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        // Not implemented
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): void
    {
        // Not implemented
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug): RecipeDisplayResource
    {
        return $this->recipeSearchService->getBySlug($slug);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): void
    {
        // Not implemented
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): void
    {
        // Not implemented
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): void
    {
        // Not implemented
    }
}
