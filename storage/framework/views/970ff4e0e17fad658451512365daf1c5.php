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
        <a href="<?php echo e(route('recipes.feasible')); ?>" class="btn btn-info mb-3">Check Feasible Recipes</a>

        <!-- Success Message -->
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- Button to Create a New Recipe -->
        <div class="d-flex justify-content-between mb-3">
            <a href="<?php echo e(route('recipes.create')); ?>" class="btn btn-primary">Create New Recipe</a>
            <a href="<?php echo e(route('inventory.index')); ?>" class="btn btn-success">Go to Inventory</a>
        </div>
        <!-- Recipes Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Ingredients</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $recipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($recipe->name); ?></td>
                        <td><?php echo e($recipe->category); ?></td>
                        <td><?php echo e($recipe->description); ?></td>
                        <td>
                            <ul>
                                <?php $__currentLoopData = $recipe->ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <?php echo e($ingredient->name); ?> - 
                                        <?php echo e($ingredient->pivot->quantity); ?> <?php echo e($ingredient->pivot->unit); ?>

                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </td>
                        <td>
                            <a href="<?php echo e(route('recipes.edit', $recipe->id)); ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form action="<?php echo e(route('recipes.destroy', $recipe->id)); ?>" method="POST" class="d-inline">
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
</body>
</html>


<?php /**PATH C:\Users\steph\ingredient-tracker\resources\views/recipes/index.blade.php ENDPATH**/ ?>