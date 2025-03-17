<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Recipe;
use App\Models\Ingredient;
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
}
