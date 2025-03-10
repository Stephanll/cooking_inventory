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
        <form action="<?php echo e(route('recipes.update', $recipe->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="mb-3">
                <label for="name" class="form-label">Recipe Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo e($recipe->name); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo e($recipe->description); ?></textarea>
            </div>

            <!-- Ingredients Section -->
            <div class="mb-3">
                <label class="form-label">Ingredients</label>
                <div id="ingredients-container">
                    <?php $__currentLoopData = $recipe->ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="ingredient-row mb-3">
                            <select class="form-control mb-2" name="ingredients[]" required>
                                <option value="">Select Ingredient</option>
                                <?php $__currentLoopData = $ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($ing->id); ?>" <?php echo e($ingredient->id == $ing->id ? 'selected' : ''); ?>>
                                        <?php echo e($ing->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <input type="number" class="form-control mb-2" name="quantities[]" step="0.01" value="<?php echo e($ingredient->pivot->quantity); ?>" placeholder="Quantity" required>
                            <select class="form-control" name="units[]" required>
                                <option value="grams" <?php echo e($ingredient->pivot->unit == 'grams' ? 'selected' : ''); ?>>Grams</option>
                                <option value="liters" <?php echo e($ingredient->pivot->unit == 'liters' ? 'selected' : ''); ?>>Liters</option>
                                <option value="pieces" <?php echo e($ingredient->pivot->unit == 'pieces' ? 'selected' : ''); ?>>Pieces</option>
                            </select>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <button type="button" class="btn btn-secondary" id="add-ingredient">Add Ingredient</button>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Update Recipe</button>
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
                <select class="form-control" name="ingredients[]" required>
                    <option value="">Select Ingredient</option>
                    <?php $__currentLoopData = $ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($ingredient->id); ?>"><?php echo e($ingredient->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
</html><?php /**PATH C:\Users\steph\ingredient-tracker\resources\views/recipes/edit.blade.php ENDPATH**/ ?>