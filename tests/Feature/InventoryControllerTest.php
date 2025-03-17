<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Inventory;
use App\Models\User;
use App\Models\Ingredient;

class InventoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_successful_response()
    {
        // Arrange: Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Arrange: Create an ingredient and an inventory item for the user
        $ingredient = Ingredient::factory()->create();
        Inventory::create([
            'user_id' => $user->id,
            'ingredient_id' => $ingredient->id,
            'quantity' => 500,
            'unit' => 'grams',
        ]);

        // Act: Visit the inventory index page
        $response = $this->get(route('inventory.index'));

        // Assert: The response is successful
        $response->assertStatus(200);

        // Assert: The correct view is returned
        $response->assertViewIs('inventory.index');
    }
    
    public function test_store_creates_new_ingredient()
    {
        // Arrange: Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Arrange: Create an ingredient
        $ingredient = Ingredient::factory()->create();

        // Act: Submit the form to create a new inventory item
        $response = $this->post(route('inventory.store'), [
            'ingredient_id' => $ingredient->id,
            'quantity' => 500,
            'unit' => 'grams',
            'expiration_date' => '2222-02-22',
        ]);

        // Assert: The response redirects to the index page
        $response->assertRedirect(route('inventory.index'));

        // Assert: The inventory item was created in the database
        $this->assertDatabaseHas('inventory', [
            'ingredient_id' => $ingredient->id,
            'quantity' => 500,
            'unit' => 'grams',
            'expiration_date' => '2222-02-22',
        ]);
    }

    public function test_update_modifies_existing_ingredient()
    {
        // Arrange: Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Arrange: Create an inventory item for the authenticated user
        $inventory = Inventory::factory()->create(['user_id' => $user->id]);

        // Arrange: Create an ingredient
        $ingredient = Ingredient::factory()->create();

        // Act: Submit the form to update the inventory item
        $response = $this->put(route('inventory.update', $inventory->id), [
            'ingredient_id' => $ingredient->id,
            'quantity' => 1000,
            'unit' => 'liters',
            'expiration_date' => '2222-02-22',
        ]);

        // Assert: The response redirects to the index page
        $response->assertRedirect(route('inventory.index'));

        // Assert: The inventory item was updated in the database
        $this->assertDatabaseHas('inventory', [
            'id' => $inventory->id,
            'ingredient_id' => $ingredient->id,
            'quantity' => 1000,
            'unit' => 'liters',
            'expiration_date' => '2222-02-22',
        ]);
    }

    public function test_destroy_deletes_ingredient()
    {
        // Arrange: Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);
    
        // Arrange: Create an inventory item for the authenticated user
        $inventory = Inventory::factory()->create(['user_id' => $user->id]);
    
        // Act: Submit the form to delete the inventory item
        $response = $this->delete(route('inventory.destroy', $inventory->id));
    
        // Assert: The response redirects to the index page
        $response->assertRedirect(route('inventory.index'));
    
        // Assert: The inventory item was deleted from the database
        $this->assertDatabaseMissing('inventory', [
            'id' => $inventory->id,
        ]);
    }

}