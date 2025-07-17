<?php

namespace Tests\Feature\Recipes;

use App\Models\Recipe;
use App\Models\RecipeStep;
use App\Models\Ingredient;
use App\Models\User;
use Tests\TestCase;

class RecipeFeatureBaseTestCase extends TestCase
{
    protected User $testUser;
    protected array $testRecipes = [];

    protected function setUpRecipeTest(int $numRecipesToCreate = 1): void
    {
        $this->testUser = $this->createTestUser();
        $this->addTestRecipe($numRecipesToCreate);
    }

    protected function addTestRecipe(int $numToCreate = 1, int $testUserId = null): void
    {
        for($x=0;$x<$numToCreate;$x++) {
            // Create a test recipe
            $recipeTest = Recipe::factory()->create([
                'user_id' => $testUserId ?? $this->testUser->id,
            ]);

            // Create 4 ingredients
            Ingredient::factory()->count(4)->create();

            // Attach only 3 ingredients
            $recipeTest->ingredients()->sync(
                Ingredient::all()
                    ->random(3)
                    ->pluck('id')
                    ->toArray()
            );

            // Generate some fake recipe steps
            RecipeStep::factory()->count(5)->setOrder(0)->create([
                'recipe_id' => $recipeTest->id,
            ]);

            // Keep our test models in an array for easy tracking
            $this->testRecipes[] = $recipeTest;
        }
    }
}
