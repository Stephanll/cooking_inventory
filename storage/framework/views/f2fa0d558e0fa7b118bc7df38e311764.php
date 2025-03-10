<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Recipe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Create Recipe</h1>

        <!-- Form to Create a Recipe -->
        <form action="<?php echo e(route('recipes.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="name" class="form-label">Recipe Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>

            <!-- Ingredients Section -->
            <div class="mb-3">
                <label class="form-label">Ingredients</label>
                <div id="ingredients-container">
                    <div class="ingredient-row mb-3">
                        <select class="form-control mb-2" name="ingredients[]" required>
                            <option value="">Select Ingredient</option>
                            <?php $__currentLoopData = $ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($ingredient->id); ?>"><?php echo e($ingredient->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <input type="number" class="form-control mb-2" name="quantities[]" step="0.01" placeholder="Quantity" required>
                        <select class="form-control mb-2" name="units[]" required>
                            <option value="grams">Grams</option>
                            <option value="liters">Liters</option>
                            <option value="pieces">Pieces</option>
                        </select>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" id="add-ingredient">Add Ingredient</button>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Create Recipe</button>
            <a href="<?php echo e(route('recipes.index')); ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- JavaScript to Add More Ingredients -->
    <script>
        document.getElementById('add-ingredient').addEventListener('click', function () {
            const container = document.getElementById('ingredients-container');
            const newRow = document.createElement('div');
            newRow.classList.add('ingredient-row', 'mb-3');
            newRow.innerHTML = `
                <select class="form-control mb-2" name="ingredients[]" required>
                    <option value="">Select Ingredient</option>
                    <?php $__currentLoopData = $ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($ingredient->id); ?>"><?php echo e($ingredient->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <input type="number" class="form-control mb-2" name="quantities[]" step="0.01" placeholder="Quantity" required>
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
</html><?php /**PATH C:\Users\steph\ingredient-tracker\resources\views/recipes/create.blade.php ENDPATH**/ ?>