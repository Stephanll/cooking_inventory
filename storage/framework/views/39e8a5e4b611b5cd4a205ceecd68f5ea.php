<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Navigation Headers -->
    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
            <!-- App Title with Custom Chef Hat SVG Logo (Linked to Inventory) -->
            <a href="<?php echo e(route('inventory.index')); ?>" class="d-flex align-items-center gap-2 text-decoration-none">
                <!-- Chef Hat SVG Logo -->
                <img src="<?php echo e(asset('images/chef-svgrepo-com.svg')); ?>" alt="Chef Hat Logo" style="width: 40px; height: 40px;">
                <!-- App Title -->
                <h1 class="mb-0" style="font-size: 1.8rem; font-weight: 600; color: inherit;">Cooking Manager</h1>
            </a>

            <!-- Navigation Links -->
            <div class="d-flex gap-3">
                <a href="<?php echo e(route('inventory.index')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('inventory.index') ? 'active' : ''); ?>">
                    <i class="bi bi-box"></i> Inventory
                </a>
                <a href="<?php echo e(route('shopping-list.index')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('shopping-list.index') ? 'active' : ''); ?>">
                    <i class="bi bi-cart"></i> Shopping List
                </a>
                <a href="<?php echo e(route('recipes.index')); ?>" class="nav-link-custom <?php echo e(request()->routeIs('recipes.index') ? 'active' : ''); ?>">
                    <i class="bi bi-book"></i> Recipes
                </a>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        .nav-link-custom {
            text-decoration: none;
            color: black;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        a.text-decoration-none {
           color: black; /* Force text color to stay black */
        }

        .nav-link-custom i {
            font-size: 1.2rem;
        }

        .nav-link-custom.active {
            border: 2px solid rgba(0, 123, 255, 0.5); /* Transparent blue box */
            background: rgba(0, 123, 255, 0.1);
            border-radius: 8px;
        }

        .nav-link-custom:hover {
            color: #007bff;
        }
    </style>
    
    <div class="container mt-5">
        <h1 class="mb-4">Shopping List</h1>

        <!-- Success/Error Messages -->
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
    
        <!-- Shopping List Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Ingredient</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $shoppingLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shoppingList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($shoppingList->ingredient->name); ?></td>
                        <td><?php echo e($shoppingList->quantity); ?></td>
                        <td><?php echo e($shoppingList->unit); ?></td>
                        <td>
                            <form action="<?php echo e(route('shopping-list.destroy', $shoppingList->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <!-- Finish Shopping Button -->
        <form action="<?php echo e(route('shopping-list.finish')); ?>" method="POST" class="mt-4 mb-2">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-success">Finish Shopping</button>
        </form>

        <a href="<?php echo e(route('inventory.index')); ?>" class="btn btn-secondary mb-3">Go to Inventory</a>

        <!-- Add Ingredient Form -->
        <h2 class="mt-5">Add Ingredient</h2>
        <form action="<?php echo e(route('shopping-list.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="ingredient_id" class="form-label">Ingredient</label>
                <select class="form-control" id="ingredient_id" name="ingredient_id" required>
                    <option value="">Select Ingredient</option>
                    <?php $__currentLoopData = $ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($ingredient->id); ?>"><?php echo e($ingredient->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" step="0.01" min="0.01" required>
            </div>
            <div class="mb-3">
                <label for="unit" class="form-label">Unit</label>
                <select class="form-control" id="unit" name="unit" required>
                    <option value="grams">Grams</option>
                    <option value="liters">Liters</option>
                    <option value="pieces">Pieces</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add to Shopping List</button>
        </form>
    </div>
</body>
</html><?php /**PATH C:\Users\steph\ingredient-tracker\resources\views/shopping-list/index.blade.php ENDPATH**/ ?>