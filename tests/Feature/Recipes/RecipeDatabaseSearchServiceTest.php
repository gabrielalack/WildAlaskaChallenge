<?php

namespace Tests\Feature\Recipes;

use App\Http\Requests\Recipes\RecipeSearchRequest;
use App\Models\Recipe;
use App\Models\RecipeStep;
use App\Models\Ingredient;
use App\Models\User;
use App\Services\Recipes\RecipeDatabaseSearchService;

class RecipeDatabaseSearchServiceTest extends RecipeFeatureBaseTestCase
{
    /**
     * Test DB search service basic list search functionality
     * 
     * ASSERT:
     *  - We should retrieve all items from the request
     */
    public function testRecipeList(): void
    {
        // Create basic recipe data
        // with 4 test recipes
        $this->setUpRecipeTest(4);
        $dbSearchService = new RecipeDatabaseSearchService();

        // Test basic unfiltered list -- no parameters
        $request = RecipeSearchRequest::create('/api/recipe', 'GET');
        $results = $dbSearchService->getListFromRequest($request);

        // We should have all 4 items in our result
        $this->assertCount(4, $results->items());
    }

    /**
     * Test DB search service searching by user email
     * 
     * ASSERT:
     *  - We should only get the results matching the email input
     *  - Result matches the correct author
     */
    public function testRecipeUserEmailSearch(): void
    {
        // Create basic recipe data
        // with 4 test recipes
        $this->setUpRecipeTest(4);
        $dbSearchService = new RecipeDatabaseSearchService();

        // Add a second user and create 2 additional recipes using their id
        $secondUser = $this->createTestUser();
        $this->addTestRecipe(2, $secondUser->id);

        // Test request with an email input
        $request = RecipeSearchRequest::create('/api/recipe', 'GET', ['email' => $secondUser->email]);
        $results = $dbSearchService->getListFromRequest($request);

        // We should have 2 items in this result
        $this->assertCount(2, $results->items());

        // And it should be the correct author
        $this->assertEquals($secondUser->email, $results->items()[0]->email);
    }

    /**
     * Test the ingredient input
     * 
     * ASSERT:
     *  - We should only match the result with the specified ingredient
     *  - Using the author email in tandem should still return the result
     *  - Using a different author email should be a mismatch and return no results
     */
    public function testRecipeIngredientSearch(): void
    {
        // Create basic recipe data
        // with 4 test recipes
        $this->setUpRecipeTest(4);
        $dbSearchService = new RecipeDatabaseSearchService();

        // Add a second user and create 2 additional recipes using their id
        $secondUser = $this->createTestUser();
        $this->addTestRecipe(2, $secondUser->id);

        // Use a test case guaranteed to be unique
        $ingredientSearchTerm = 'totesNotButterAtAll';

        // We will ensure 1 recipe has the distinct ingredient 
        $this->testRecipes[1]->ingredients()->create(['name' => $ingredientSearchTerm]);

        // Perform an ingredient search
        $request = RecipeSearchRequest::create('/api/recipe', 'GET', ['ingredient' => $ingredientSearchTerm]);
        $results = $dbSearchService->getListFromRequest($request);

        // We should have 1 items in this result
        $this->assertCount(1, $results->items());

        // Additionally search using the user email to ensure we still get the result in combination
        $request = RecipeSearchRequest::create('/api/recipe', 'GET', ['ingredient' => $ingredientSearchTerm, 'email' => $this->testUser->email]);
        $results = $dbSearchService->getListFromRequest($request);

        // We should still have 1 item
        // (Proving we get the user AND the ingredient logic)
        $this->assertCount(1, $results->items());

        // Search by ingredient AND the second user email where we know there should be no matching result
        $request = RecipeSearchRequest::create('/api/recipe', 'GET', ['ingredient' => $ingredientSearchTerm, 'email' => $secondUser->email]);
        $results = $dbSearchService->getListFromRequest($request);

        // Should have no results (must match both)
        $this->assertCount(0, $results->items());
    }

    /**
     * Test the search term input against the recipe name
     * 
     * Assert:
     *  - search term retrieves a result based on the recipe name
     */
    public function testSearchTermOnRecipeName(): void
    {
        // Create basic recipe data
        // with 4 test recipes
        $this->setUpRecipeTest(4);
        $dbSearchService = new RecipeDatabaseSearchService();

        // Set a predictable name on a recipe
        $this->testRecipes[1]->update(['name' => 'yummy gumbo delight grandmas recipe']);

        $request = RecipeSearchRequest::create('/api/recipe', 'GET', ['search_term' => 'gumbo delight']);
        $results = $dbSearchService->getListFromRequest($request);

        // We have 1 result
        $this->assertCount(1, $results->items());

        // And its the matching result
        $this->assertEquals($this->testRecipes[1]->id, $results->items()[0]->id);
    }


    /**
     * Test the search term input against the recipe description
     * 
     * Assert:
     *  - search term retrieves a result based on the recipe description
     */
    public function testSearchTermOnDescription(): void
    {
        // Create basic recipe data
        // with 4 test recipes
        $this->setUpRecipeTest(4);
        $dbSearchService = new RecipeDatabaseSearchService();

        // Set a predictable description on a recipe
        $this->testRecipes[2]->update(['description' => 'so good youll slap ya mamma, wewww!']);

        $request = RecipeSearchRequest::create('/api/recipe', 'GET', ['search_term' => 'slap ya mamma']);
        $results = $dbSearchService->getListFromRequest($request);

        // We have 1 result
        $this->assertCount(1, $results->items());

        // And its the matching result
        $this->assertEquals($this->testRecipes[2]->id, $results->items()[0]->id);
    }


    /**
     * Test the search term input against the recipe ingredients
     * 
     * Assert:
     *  - search term retrieves a result based on the recipe ingredients
     */
    public function testSearchTermOnIngredients(): void
    {
        // Create basic recipe data
        // with 4 test recipes
        $this->setUpRecipeTest(4);
        $dbSearchService = new RecipeDatabaseSearchService();

        // Set a predictable ingredient
        $this->testRecipes[3]->ingredients()->create(['name' => '1 pound totallyNotButterAtAll']);

        $request = RecipeSearchRequest::create('/api/recipe', 'GET', ['search_term' => 'totallyNotButterAtAll']);
        $results = $dbSearchService->getListFromRequest($request);

        // We have 1 result
        $this->assertCount(1, $results->items());

        // And its the matching result
        $this->assertEquals($this->testRecipes[3]->id, $results->items()[0]->id);
    }


    /**
     * Test the search term input against the recipe steps
     * 
     * Assert:
     *  - search term retrieves a result based on the recipe steps
     */
    public function testSearchTermOnRecipeSteps(): void
    {
        // Create basic recipe data
        // with 4 test recipes
        $this->setUpRecipeTest(4);
        $dbSearchService = new RecipeDatabaseSearchService();

        // Set a predictable step description
        $this->testRecipes[3]->steps[0]->update(['step_description' => 'beat with giant hammer until tenderized']);

        $request = RecipeSearchRequest::create('/api/recipe', 'GET', ['search_term' => 'giant hammer']);
        $results = $dbSearchService->getListFromRequest($request);

        // We have 1 result
        $this->assertCount(1, $results->items());

        // And its the matching result
        $this->assertEquals($this->testRecipes[3]->id, $results->items()[0]->id);
    }

    /**
     * Test combination of ingredient and search term
     * 
     * Main assertion: Test will match on the user AND ingredient AND (name, description, ingredient, or step)
     * 
     * ASSERT
     *  - Will only match records that have all conditions met
     *  - Specifying a user will not return other matching results belonging to other users
     */
    public function testIngredientAndSearchTerm(): void
    {
        // Create basic recipe data
        // with 4 test recipes
        $this->setUpRecipeTest(4);
        $dbSearchService = new RecipeDatabaseSearchService();

        // Add a second user and create 2 additional recipes using their id
        // To demonstrate these will not get included with the user filter
        $secondUser = $this->createTestUser();
        $this->addTestRecipe(2, $secondUser->id);

        // We will need a specific ingredient all test recipes share
        $commonIngredient = Ingredient::create(['name' => 'Stalk Of Celery']);

        // We will ensure 1 recipe has a our target ingredient 
        $this->testRecipes[0]->ingredients()->attach($commonIngredient);

        // Ensure we have a matching name
        $this->testRecipes[1]->update(['description' => 'Jennys big fat souffle']);
        $this->testRecipes[1]->ingredients()->attach($commonIngredient);

        // And a matching step
        $this->testRecipes[2]->steps[0]->update(['step_description' => 'Add a big fat heaping of salt']);
        $this->testRecipes[2]->ingredients()->attach($commonIngredient);

        // And a matching ingredient that will match the search term
        $this->testRecipes[3]->ingredients()->create(['name' => '1 big fat spoon of butter']);
        $this->testRecipes[3]->ingredients()->attach($commonIngredient);

        // Perform a search will all options
        $request = RecipeSearchRequest::create(
                        '/api/recipe', 
                        'GET', 
                        [
                            'email' => $this->testUser->email,
                            'ingredient' => 'stalk of celery', 
                            'search_term' => 'big fat',
                        ]
                    );
        $results = $dbSearchService->getListFromRequest($request);

        // We should have 3 matches, because the first recipe does not match
        // the key word so it wont match all the conditions
        $this->assertCount(3, $results->items());

        // Change the first recipe to include a matching term
        $this->testRecipes[0]->update(['name' => 'big fat salad']);

         // Re-run the call
        $request = RecipeSearchRequest::create(
                        '/api/recipe', 
                        'GET', 
                        [
                            'email' => $this->testUser->email,
                            'ingredient' => 'stalk of celery', 
                            'search_term' => 'big fat',
                        ]
                    );
        $results = $dbSearchService->getListFromRequest($request);

        // Will now match all conditions on the 4 recipes
        $this->assertCount(4, $results->items());

        // Re-run the call with user 2
        $request = RecipeSearchRequest::create(
                        '/api/recipe', 
                        'GET', 
                        [
                            'email' => $secondUser->email,
                            'ingredient' => 'stalk of celery', 
                            'search_term' => 'big fat',
                        ]
                    );
        $results = $dbSearchService->getListFromRequest($request);

        // Should have no matches
        $this->assertCount(0, $results->items());
    }
}
