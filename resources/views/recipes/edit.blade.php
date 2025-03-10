<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Edit Recipe</h1>

        <!-- Form to Edit a Recipe -->
        <form action="{{ route('recipes.update', $recipe->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Recipe Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $recipe->name }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ $recipe->description }}</textarea>
            </div>

            <!-- Ingredients Section -->
            <div class="mb-3">
                <label class="form-label">Ingredients</label>
                <div id="ingredients-container">
                    @foreach ($recipe->ingredients as $index => $ingredient)
                        <div class="ingredient-row mb-3">
                            <select class="form-control mb-2" name="ingredients[]" required>
                                <option value="">Select Ingredient</option>
                                @foreach ($ingredients as $ing)
                                    <option value="{{ $ing->id }}" {{ $ingredient->id == $ing->id ? 'selected' : '' }}>
                                        {{ $ing->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" class="form-control mb-2" name="quantities[]" step="0.01" value="{{ $ingredient->pivot->quantity }}" placeholder="Quantity" required>
                            <select class="form-control" name="units[]" required>
                                <option value="grams" {{ $ingredient->pivot->unit == 'grams' ? 'selected' : '' }}>Grams</option>
                                <option value="liters" {{ $ingredient->pivot->unit == 'liters' ? 'selected' : '' }}>Liters</option>
                                <option value="pieces" {{ $ingredient->pivot->unit == 'pieces' ? 'selected' : '' }}>Pieces</option>
                            </select>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary" id="add-ingredient">Add Ingredient</button>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Update Recipe</button>
            <a href="{{ route('recipes.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- JavaScript to Add More Ingredients -->
    <script>
        document.getElementById('add-ingredient').addEventListener('click', function () {
            const container = document.getElementById('ingredients-container');
            const newRow = document.createElement('div');
            newRow.classList.add('ingredient-row', 'mb-3');
            newRow.innerHTML = `
                <select class="form-control" name="ingredients[]" required>
                    <option value="">Select Ingredient</option>
                    @foreach ($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                    @endforeach
                </select>
                <input type="number" class="form-control" name="quantities[]" step="0.01" placeholder="Quantity" required>
                <select class="form-control" name="units[]" required>
                    <option value="grams">Grams</option>
                    <option value="liters">Liters</option>
                    <option value="pieces">Pieces</option>
                </select>
            `;
            container.appendChild(newRow);
        });
    </script>
</body>
</html>