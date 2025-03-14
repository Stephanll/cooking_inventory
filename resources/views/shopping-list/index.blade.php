<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    
    <div class="container mt-5">
        <h1 class="mb-4">Shopping List</h1>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    
        <!-- Shopping List Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Ingredient</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($shoppingLists as $shoppingList)
                    <tr>
                        <td>{{ $shoppingList->ingredient->name }}</td>
                        <td>{{ $shoppingList->quantity }}</td>
                        <td>{{ $shoppingList->unit }}</td>
                        <td>
                            <form action="{{ route('shopping-list.destroy', $shoppingList->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Finish Shopping Button -->
        <form action="{{ route('shopping-list.finish') }}" method="POST" class="mt-4 mb-2">
            @csrf
            <button type="submit" class="btn btn-success">Finish Shopping</button>
        </form>

        <a href="{{ route('inventory.index') }}" class="btn btn-secondary mb-3">Go to Inventory</a>

        <!-- Add Ingredient Form -->
        <h2 class="mt-5">Add Ingredient</h2>
        <form action="{{ route('shopping-list.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="ingredient_id" class="form-label">Ingredient</label>
                <select class="form-control" id="ingredient_id" name="ingredient_id" required>
                    <option value="">Select Ingredient</option>
                    @foreach ($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" step="0.01" min="0.01" required>
            </div>
            <div class="mb-3">
                <label for="unit" class="form-label">Unit</label>
                <select class="form-control" id="unit" name="unit" required>
                    <option value="grams">Grams</option>
                    <option value="liters">Liters</option>
                    <option value="pieces">Pieces</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add to Shopping List</button>
        </form>
    </div>
</body>
</html>