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
        <a href="{{ route('inventory.create') }}" class="btn btn-primary mb-3">Add New Inventory Item</a>

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
                }, 5000); // 5000 milliseconds = 5 seconds
            }
        });
    </script>
</body>
</html>

<!-- JavaScript to Hide Flash Message -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const flashMessage = document.getElementById('flash-message');

        if (flashMessage) {
            // Add the fade-out class
            flashMessage.classList.add('fade-out');

            // Add the hide class after 5 seconds to trigger the fade-out
            setTimeout(function () {
                flashMessage.classList.add('hide');
            }, 3000);

            // Remove the element from the DOM after the fade-out completes
            setTimeout(function () {
                flashMessage.remove();
            }, 4000); 
        }
    });
</script>

