<?php

namespace Tests\Feature\Recipes;

class RecipeModelTest extends RecipeFeatureBaseTestCase
{
    /**
     * Test that the Recipe Model basic relationships function as expected
     * Leveraging the base test data
     * 
     * ASSERT:
     *  - Ingredients relationship populates
     *  - Steps relationship populates
     *  - Steps are retrieved in order
     *  - Author relationship is populated
     *  - Sensitive fields are not present on the Author/User relationship 
     */
    public function testModelRelationships(): void
    {
        $this->setUpRecipeTest();

        // Assert the relation counts are correct from our initializer
        $this->assertCount(3, $this->testRecipes[0]->ingredients);
        $this->assertCount(5, $this->testRecipes[0]->steps);

        // Assert steps are set and ordered correctly
        for ($x=0;$x<5;$x++) {
            $this->assertEquals($x, $this->testRecipes[0]->steps[$x]->order);
        }

        // Assert author is set
        $this->assertEquals($this->testUser->email, $this->testRecipes[0]->author->email);

        // Assert sensitive user model fields are not present in this relationship
        $this->assertNull($this->testRecipes[0]->author->password);
        $this->assertNull($this->testRecipes[0]->author->remember_token);
    }
}
