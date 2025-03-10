<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Recipes</h1>
        <a href="{{ route('recipes.feasible') }}" class="btn btn-info mb-3">Check Feasible Recipes</a>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Button to Create a New Recipe -->
        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('recipes.create') }}" class="btn btn-primary">Create New Recipe</a>
            <a href="{{ route('inventory.index') }}" class="btn btn-success">Go to Inventory</a>
        </div>
        <!-- Recipes Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Ingredients</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recipes as $recipe)
                    <tr>
                        <td>{{ $recipe->name }}</td>
                        <td>{{ $recipe->description }}</td>
                        <td>
                            <ul>
                                @foreach ($recipe->ingredients as $ingredient)
                                    <li>
                                        {{ $ingredient->name }} - 
                                        {{ $ingredient->pivot->quantity }} {{ $ingredient->pivot->unit }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <a href="{{ route('recipes.edit', $recipe->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>


