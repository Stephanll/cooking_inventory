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
        <a href="<?php echo e(route('recipes.feasible')); ?>" class="btn btn-info mb-3">Check Feasible Recipes</a>
        <a href="<?php echo e(route('inventory.create')); ?>" class="btn btn-primary mb-3">Add New Inventory Item</a>
        <a href="<?php echo e(route('recipes.index')); ?>" class="btn btn-success mb-3">Go to Recipes</a>
        <a href="<?php echo e(route('shopping-list.index')); ?>" class="btn btn-secondary mb-3">Go to shopping list</a>
        <!-- Add Ingredient Button -->
        <button id="add-ingredient-button" class="btn btn-primary mb-3">Add Ingredient</button>

        <!-- Add Ingredient Form (Initially Hidden) -->
        <div id="add-ingredient-form" style="display: none;">
            <form action="<?php echo e(route('ingredients.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="name">Ingredient Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" name="category" id="category" class="form-control mb-2" required>
                </div>
                <button type="submit" class="btn btn-success mb-2">Save</button>
            </form>
        </div>

        <?php if(session('success')): ?>
            <div id="flash-message" class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

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
                <?php $__currentLoopData = $inventory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->ingredient->name); ?></td>
                        <td><?php echo e($item->quantity); ?></td>
                        <td><?php echo e($item->unit); ?></td>
                        <td><?php echo e($item->ingredient->category); ?></td>
                        <td><?php echo e($item->expiration_date); ?></td>
                        <td>
                            <a href="<?php echo e(route('inventory.edit', $item->id)); ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form action="<?php echo e(route('inventory.destroy', $item->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
        document.getElementById('add-ingredient-button').addEventListener('click', function () {
            const form = document.getElementById('add-ingredient-form');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        });
    </script>
</body>
</html><?php /**PATH C:\Users\steph\ingredient-tracker\resources\views/inventory/index.blade.php ENDPATH**/ ?>