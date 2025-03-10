<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Inventory</h1>
        <a href="{{ route('recipes.feasible') }}" class="btn btn-info mb-3">Check Feasible Recipes</a>
        <a href="{{ route('inventory.create') }}" class="btn btn-primary mb-3">Add New Inventory Item</a>
        <a href="{{ route('recipes.index') }}" class="btn btn-success mb-3">Go to Recipes</a>
        <a href="{{ route('shopping-list.index') }}" class="btn btn-secondary mb-3">Go to shopping list</a>

        @if (session('success'))
            <div id="flash-message" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Ingredient</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Category</th>
                    <th>Expiration Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventory as $item)
                    <tr>
                        <td>{{ $item->ingredient->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>{{ $item->ingredient->category }}</td>
                        <td>{{ $item->expiration_date }}</td>
                        <td>
                            <a href="{{ route('inventory.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('inventory.destroy', $item->id) }}" method="POST" class="d-inline">
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