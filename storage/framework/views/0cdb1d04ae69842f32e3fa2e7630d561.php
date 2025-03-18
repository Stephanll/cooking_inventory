<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feasible Recipes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php if(session('success')): ?>
        <div id="flash-message" class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div id='flash-message' class="alert alert-danger">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Recipes You Can Make</h1>

        <!-- Button to Go Back to Recipes or Inventory -->
        <a href="<?php echo e(route('inventory.index')); ?>" class="btn btn-success mb-3">Go to Inventory</a>
        <a href="<?php echo e(route('recipes.index')); ?>" class="btn btn-secondary mb-3">Back to All Recipes</a>

        <!-- List of Recipes -->
        <?php $__currentLoopData = $feasibleRecipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feasibleRecipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e($feasibleRecipe['recipe']->name); ?></h5>
                    <p class="card-text"><?php echo e($feasibleRecipe['recipe']->description); ?></p>

                    <?php if($feasibleRecipe['canMake']): ?>
                        <div class="alert alert-success">
                            You can make this recipe!
                        </div>
                    <?php else: ?>
                    <div class="alert alert-danger">
                        You cannot make this recipe. Missing ingredients:
                        <ul>
                            <?php $__currentLoopData = $feasibleRecipe['missingIngredients']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $missing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <?php echo e($missing['name']); ?> (Required: <?php echo e($missing['required']); ?>, Available: <?php echo e($missing['available']); ?>)
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <!-- Add "Update Shopping Cart" Button -->
                        <form action="<?php echo e(route('shopping-list.update-from-recipe')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="recipe_id" value="<?php echo e($feasibleRecipe['recipe']->id); ?>">
                            <button type="submit" class="btn btn-warning">Update Shopping Cart</button>
                        </form>
                    </div>
                    <?php endif; ?>

                       <!-- Cook Recipe Form -->
                        <form action="<?php echo e(route('recipes.cook', $feasibleRecipe['recipe']->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-primary mb-2">Cook Recipe</button>
                        </form>

                        <!-- Undo Cooking Form -->
                        <form action="<?php echo e(route('recipes.undoCook', $feasibleRecipe['recipe']->id)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-warning">Undo Cooking</button>
                        </form>
                    
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
</html><?php /**PATH C:\Users\steph\ingredient-tracker\resources\views/recipes/feasible.blade.php ENDPATH**/ ?>