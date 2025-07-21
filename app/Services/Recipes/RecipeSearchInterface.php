<?php

namespace App\Services\Recipes;

use App\Http\Resources\Recipes\RecipeListCollectionResource;
use App\Http\Requests\Recipes\RecipeSearchRequest;
use App\Http\Resources\Recipes\RecipeDisplayResource;

interface RecipeSearchInterface 
{
    public function getListFromRequest(RecipeSearchRequest $request): RecipeListCollectionResource;

    public function getBySlug(string $slug): RecipeDisplayResource;
}
