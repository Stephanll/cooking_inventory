<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Inventory Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Add Inventory Item</h1>
        <form action="{{ route('inventory.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="ingredient_id" class="form-label">Ingredient</label>
                <select class="form-control" id="ingredient_id" name="ingredient_id" required>
                    @foreach ($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" step="0.01" min="0" required>
            </div>
            <div class="mb-3">
                <label for="unit" class="form-label">Unit</label>
                <select class="form-control" id="unit" name="unit" required>
                    <option value="grams">Grams</option>
                    <option value="pieces">Pieces</option>
                    <option value="liters">Liters</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="expiration_date" class="form-label">Expiration Date</label>
                <input type="date" class="form-control" id="expiration_date" name="expiration_date" required>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>



<script>
    document.querySelector('form').addEventListener('submit', function (event) {
        const unit = document.getElementById('unit').value;
        if (unit !== 'grams' && unit !== 'pieces' && unit !== 'liters') {
            alert('Please select either grams, pieces or liters for the unit.');
            event.preventDefault(); // Prevent form submission
        }
    });
</script>