<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InventoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_successful_response()
    {
        // Act: Visit the inventory index page
        $response = $this->get('/inventory');

        // Assert: The response is successful
        $response->assertStatus(200);

        // Assert: The correct view is returned
        $response->assertViewIs('inventory.index');
    }

    public function test_store_creates_new_ingredient()
    {
        // Arrange: Define the ingredient data
        $ingredientData = [
            'name' => 'Flour',
            'quantity' => 500,
            'unit' => 'grams',
        ];

        // Act: Submit the form to add a new ingredient
        $response = $this->post('/inventory', $ingredientData);

        // Assert: The ingredient is saved to the database
        $this->assertDatabaseHas('inventory', $ingredientData);

        // Assert: The user is redirected to the inventory index
        $response->assertRedirect('/inventory');
    }

    public function test_update_modifies_existing_ingredient()
    {
        // Arrange: Create an ingredient in the database
        $ingredient = \App\Models\Inventory::factory()->create();

        // Arrange: Define the updated data
        $updatedData = [
            'name' => 'Sugar',
            'quantity' => 1000,
            'unit' => 'grams',
        ];

        // Act: Submit the form to update the ingredient
        $response = $this->put("/inventory/{$ingredient->id}", $updatedData);

        // Assert: The ingredient is updated in the database
        $this->assertDatabaseHas('inventory', $updatedData);

        // Assert: The user is redirected to the inventory index
        $response->assertRedirect('/inventory');
    }

    public function test_destroy_deletes_ingredient()
    {
        // Arrange: Create an ingredient in the database
        $ingredient = \App\Models\Inventory::factory()->create();

        // Act: Submit the request to delete the ingredient
        $response = $this->delete("/inventory/{$ingredient->id}");

        // Assert: The ingredient is deleted from the database
        $this->assertDatabaseMissing('inventory', ['id' => $ingredient->id]);

        // Assert: The user is redirected to the inventory index
        $response->assertRedirect('/inventory');
    }

}