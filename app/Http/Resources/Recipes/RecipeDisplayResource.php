<?php

namespace App\Http\Resources\Recipes;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeDisplayResource extends JsonResource
{

    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = null; // Disables wrapping for this resource

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
            'ingredients' => $this->ingredients->pluck('name')->toArray(),
            'steps' => $this->steps->pluck('step_description')->toArray(),
        ];
    }
}
