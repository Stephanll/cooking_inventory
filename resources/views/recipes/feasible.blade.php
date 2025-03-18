<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feasible Recipes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    @if (session('success'))
        <div id="flash-message" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div id='flash-message' class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="container mt-5">
        <h1 class="mb-4">Recipes You Can Make</h1>

        <!-- Button to Go Back to Recipes or Inventory -->
        <a href="{{ route('inventory.index') }}" class="btn btn-success mb-3">Go to Inventory</a>
        <a href="{{ route('recipes.index') }}" class="btn btn-secondary mb-3">Back to All Recipes</a>

        <!-- List of Recipes -->
        @foreach ($feasibleRecipes as $feasibleRecipe)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $feasibleRecipe['recipe']->name }}</h5>
                    <p class="card-text">{{ $feasibleRecipe['recipe']->description }}</p>

                    @if ($feasibleRecipe['canMake'])
                        <div class="alert alert-success">
                            You can make this recipe!
                        </div>
                    @else
                    <div class="alert alert-danger">
                        You cannot make this recipe. Missing ingredients:
                        <ul>
                            @foreach ($feasibleRecipe['missingIngredients'] as $missing)
                                <li>
                                    {{ $missing['name'] }} (Required: {{ $missing['required'] }}, Available: {{ $missing['available'] }})
                                </li>
                            @endforeach
                        </ul>
                        <!-- Add "Update Shopping Cart" Button -->
                        <form action="{{ route('shopping-list.update-from-recipe') }}" method="POST">
                            @csrf
                            <input type="hidden" name="recipe_id" value="{{ $feasibleRecipe['recipe']->id }}">
                            <button type="submit" class="btn btn-warning">Update Shopping Cart</button>
                        </form>
                    </div>
                    @endif

                       <!-- Cook Recipe Form -->
                        <form action="{{ route('recipes.cook', $feasibleRecipe['recipe']->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary mb-2">Cook Recipe</button>
                        </form>

                        <!-- Undo Cooking Form -->
                        <form action="{{ route('recipes.undoCook', $feasibleRecipe['recipe']->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning">Undo Cooking</button>
                        </form>
                    
                </div>
            </div>
        @endforeach
    </div>

    <!-- JavaScript to Hide Flash Message -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const flashMessage = document.getElementById('flash-message');

        if (flashMessage) {
            setTimeout(function () {
                flashMessage.style.display = 'none';
            }, 3000); // 3000 milliseconds =  seconds
        }
    });
    </script>
</body>
</html>