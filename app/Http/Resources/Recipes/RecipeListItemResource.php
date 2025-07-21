<?php

namespace App\Http\Resources\Recipes;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeListItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'author' => $this->author->name,
            'email' => $this->author->email,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
