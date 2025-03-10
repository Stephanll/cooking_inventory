<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inventory Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Edit Inventory Item</h1>
        <form action="<?php echo e(route('inventory.update', $inventory->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="mb-3">
                <label for="ingredient_id" class="form-label">Ingredient</label>
                <select class="form-control" id="ingredient_id" name="ingredient_id" required>
                    <?php $__currentLoopData = $ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($ingredient->id); ?>" <?php echo e($inventory->ingredient_id == $ingredient->id ? 'selected' : ''); ?>>
                            <?php echo e($ingredient->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo e($inventory->quantity); ?>" step="0.01" min="0" required>
            </div>
            <div class="mb-3">
                <label for="unit" class="form-label">Unit</label>
                <select class="form-control" id="unit" name="unit" required>
                    <option value="grams" <?php echo e($inventory->unit == 'grams' ? 'selected' : ''); ?>>Grams</option>
                    <option value="pieces" <?php echo e($inventory->unit == 'pieces' ? 'selected' : ''); ?>>Pieces</option>
                    <option value="liters" <?php echo e($inventory->unit == 'liters' ? 'selected' : ''); ?>>Liters</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="expiration_date" class="form-label">Expiration Date</label>
                <input type="date" class="form-control" id="expiration_date" name="expiration_date" value="<?php echo e($inventory->expiration_date); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?php echo e(route('inventory.index')); ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>

<script>
    document.querySelector('form').addEventListener('submit', function (event) {
        const unit = document.getElementById('unit').value;
        if (unit !== 'grams' && unit !== 'pieces' && unit !== 'liters') {
            alert('Please select either grams or pieces for the unit.');
            event.preventDefault(); // Prevent form submission
        }
        if (quantity < 0) {
            alert('The quantity must be at least 0.');
            event.preventDefault(); // Prevent form submission
        }
    });
</script>
<?php /**PATH C:\Users\steph\ingredient-tracker\resources\views/inventory/edit.blade.php ENDPATH**/ ?>