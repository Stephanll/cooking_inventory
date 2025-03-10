<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inventory Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Edit Inventory Item</h1>
        <form action="{{ route('inventory.update', $inventory->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="ingredient_id" class="form-label">Ingredient</label>
                <select class="form-control" id="ingredient_id" name="ingredient_id" required>
                    @foreach ($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}" {{ $inventory->ingredient_id == $ingredient->id ? 'selected' : '' }}>
                            {{ $ingredient->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $inventory->quantity }}" step="0.01" min="0" required>
            </div>
            <div class="mb-3">
                <label for="unit" class="form-label">Unit</label>
                <select class="form-control" id="unit" name="unit" required>
                    <option value="grams" {{ $inventory->unit == 'grams' ? 'selected' : '' }}>Grams</option>
                    <option value="pieces" {{ $inventory->unit == 'pieces' ? 'selected' : '' }}>Pieces</option>
                    <option value="liters" {{ $inventory->unit == 'liters' ? 'selected' : '' }}>Liters</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="expiration_date" class="form-label">Expiration Date</label>
                <input type="date" class="form-control" id="expiration_date" name="expiration_date" value="{{ $inventory->expiration_date }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>

<script>
    document.querySelector('form').addEventListener('submit', function (event) {
        const unit = document.getElementById('unit').value;
        if (unit !== 'grams' && unit !== 'pieces' && unit !== 'liters') {
            alert('Please select either grams or pieces for the unit.');
            event.preventDefault(); // Prevent form submission
        }
        if (quantity < 0) {
            alert('The quantity must be at least 0.');
            event.preventDefault(); // Prevent form submission
        }
    });
</script>
