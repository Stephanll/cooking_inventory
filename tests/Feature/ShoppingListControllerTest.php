<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ShoppingList;
use App\Models\Ingredient;
use App\Models\User;

class ShoppingListControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_successful_response()
    {
        // Arrange: Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Act: Visit the shopping list index page
        $response = $this->get(route('shopping-list.index'));

        // Assert: The response is successful
        $response->assertStatus(200);

        // Assert: The correct view is returned
        $response->assertViewIs('shopping-list.index');
    }

    public function test_store_adds_item_to_shopping_list()
    {
        // Arrange: Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Arrange: Create an ingredient
        $ingredient = Ingredient::factory()->create();

        // Act: Submit the form to add an item to the shopping list
        $response = $this->post(route('shopping-list.store'), [
            'ingredient_id' => $ingredient->id,
            'quantity' => 500,
            'unit' => 'grams',
        ]);

        // Assert: The response redirects to the index page
        $response->assertRedirect(route('shopping-list.index'));

        // Assert: The item was added to the shopping list
        $this->assertDatabaseHas('shopping_lists', [
            'ingredient_id' => $ingredient->id,
            'quantity' => 500,
            'unit' => 'grams',
        ]);
    }

    public function test_destroy_removes_item_from_shopping_list()
    {
        
        // Arrange: Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Arrange: Create a shopping list item
        $shoppingListItem = ShoppingList::factory()->create(['user_id' => $user->id]);

        // Act: Submit the form to remove the item from the shopping list
        $response = $this->delete(route('shopping-list.destroy', $shoppingListItem->id));

        // Assert: The response redirects to the index page
        $response->assertRedirect(route('shopping-list.index'));

        // Assert: The item was removed from the shopping list
        $this->assertDatabaseMissing('shopping_lists', [
            'id' => $shoppingListItem->id,
        ]);
    }

    public function test_store_fails_with_invalid_data()
    {
        // Arrange: Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Act: Submit the form to add an item to the shopping list with invalid data
        $response = $this->post(route('shopping-list.store'), [
            'ingredient_id' => 999, // Invalid: ingredient does not exist
            'quantity' => -100, // Invalid: negative quantity
            'unit' => '', // Invalid: empty unit
        ]);

        // Assert: The response contains validation errors
        $response->assertSessionHasErrors(['ingredient_id', 'quantity', 'unit']);
    }
}