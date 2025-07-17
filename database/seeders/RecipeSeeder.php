<?php

namespace Database\Seeders;

use File;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Seed the application's database with recipes
     */
    public function run(): void
    {

        // Retrieve our users for the purpose of building an example database
        $users = User::all();

        // Load the source recipe files
        $recipeList = json_decode(File::get(database_path('seeders/data/recipes/recipes.json')));

        // Perform a database import from the source dump
        foreach ($recipeList as $recipeData) {

            // Create the main Recipe record
            $newRecipe = new Recipe();
            $newRecipe->name = $recipeData->Name;
            $newRecipe->description = $recipeData->Description;

            // For the purposes of this example app, we will assign a random user as the author
            $newRecipe->user_id = $users->random()->id;

            $newRecipe->save();

            // Save the ingredients unique in the Ingredients table
            // We want a unique entry to avoid duplication of common items such as "salt"
            foreach ($recipeData->Ingredients as $ingredientString) {
                $newOrExistingIngredient = Ingredient::firstOrCreate(
                    ['name' => $ingredientString],
                    ['name' => $ingredientString]
                );

                $newRecipe->ingredients()->attach($newOrExistingIngredient);
            }
            
            // Save the Recipe steps
            $stepOrder = 0;
            foreach ($recipeData->Method as $step) {
                $newRecipe->steps()->create([
                    'order' => $stepOrder++,
                    'step_description' => $step,
                ]);
            }
        }
    }

}
