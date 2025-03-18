<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\ShoppingList;
use App\Models\User;

class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_successful_response()
    {
        // Arrange: Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Act: Visit the recipe index page
        $response = $this->get(route('recipes.index'));

        // Assert: The response is successful
        $response->assertStatus(200);

        // Assert: The correct view is returned
        $response->assertViewIs('recipes.index');
    }

    public function test_create_new_recipe()
    {
        // Arrange: Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Arrange: Create an ingredient
        $ingredient = Ingredient::factory()->create();

        // Act: Submit the form to create a new recipe
        $response = $this->post(route('recipes.store'), [
            'name' => 'Pancakes',
            'category' => 'breakfast',
            'description' => 'Delicious pancakes',
            'ingredients' => [$ingredient->id],
            'quantities' => [200],
            'units' => ['grams'],
        ]);

        // Assert: The response redirects to the index page
        $response->assertRedirect(route('recipes.index'));

        // Assert: The recipe was created in the database
        $this->assertDatabaseHas('recipes', [
            'name' => 'Pancakes',
            'category' => 'breakfast',
            'description' => 'Delicious pancakes',
        ]);
    }

    public function test_update_modifies_existing_recipe()
    {
        // Arrange: Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Arrange: Create a recipe and an ingredient
        $recipe = Recipe::factory()->create();
        $ingredient = Ingredient::factory()->create();

        // Act: Submit the form to update the recipe
        $response = $this->put(route('recipes.update', $recipe->id), [
            'name' => 'Updated Pancakes',
            'category' => 'breakfast',
            'description' => 'Even more delicious pancakes',
            'ingredients' => [$ingredient->id],
            'quantities' => [300],
            'units' => ['grams'],
        ]);

        // Assert: The response redirects to the index page
        $response->assertRedirect(route('recipes.index'));

        // Assert: The recipe was updated in the database
        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'name' => 'Updated Pancakes',
            'category' => 'breakfast',
            'description' => 'Even more delicious pancakes',
        ]);
    }

    public function test_destroy_deletes_recipe()
    {
        // Arrange: Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Arrange: Create a recipe
        $recipe = Recipe::factory()->create();

        // Act: Submit the form to delete the recipe
        $response = $this->delete(route('recipes.destroy', $recipe->id));

        // Assert: The response redirects to the index page
        $response->assertRedirect(route('recipes.index'));

        // Assert: The recipe was deleted from the database
        $this->assertDatabaseMissing('recipes', [
            'id' => $recipe->id,
        ]);
    }

    public function test_store_fails_with_invalid_data()
    {
        // Arrange: Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Act: Submit the form to create a new recipe with invalid data
        $response = $this->post(route('recipes.store'), [
            'name' => '', // Invalid: empty name
            'category' => 'invalid', // Invalid: not in allowed categories
            'description' => 'Delicious pancakes',
            'ingredients' => [], // Invalid: no ingredients
            'quantities' => [], // Invalid: no quantities
            'units' => [], // Invalid: no units
        ]);

        // Assert: The response contains validation errors
        $response->assertSessionHasErrors(['name', 'category', 'ingredients', 'quantities', 'units']);
    }

    public function test_updateFromRecipe_adds_missing_ingredients_to_shopping_list()
    {
        // Arrange: Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Arrange: Create ingredients
        $flour = Ingredient::factory()->create(['name' => 'Flour']);
        $sugar = Ingredient::factory()->create(['name' => 'Sugar']);

        // Arrange: Create a recipe that requires 500g flour and 200g sugar
        $recipe = Recipe::factory()->create(['name' => 'Pancakes']);
        $recipe->ingredients()->attach([
            $flour->id => ['quantity' => 500, 'unit' => 'grams'],
            $sugar->id => ['quantity' => 200, 'unit' => 'grams'],
        ]);

        // Arrange: Add some ingredients to the user's inventory (but not enough)
        $user->inventory()->create([
            'ingredient_id' => $flour->id,
            'quantity' => 300, // 200g short
            'unit' => 'grams',
        ]);
        // Note: No sugar in the inventory, so 200g sugar is missing

        // Act: Call the updateFromRecipe endpoint
        $response = $this->post(route('shopping-list.update-from-recipe'), [
            'recipe_id' => $recipe->id,
        ]);

        // Assert: The response redirects to the feasible page
        $response->assertRedirect(route('recipes.feasible'));

        // Assert: The missing ingredients are added to the shopping list
        $this->assertDatabaseHas('shopping_lists', [
            'user_id' => $user->id,
            'ingredient_id' => $flour->id,
            'quantity' => 200, // 500g required - 300g available = 200g needed
            'unit' => 'grams',
        ]);
        $this->assertDatabaseHas('shopping_lists', [
            'user_id' => $user->id,
            'ingredient_id' => $sugar->id,
            'quantity' => 200, // 200g required - 0g available = 200g needed
            'unit' => 'grams',
        ]);
    }
}
