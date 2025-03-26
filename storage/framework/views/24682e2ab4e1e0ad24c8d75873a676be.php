<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
           color: black;
        }

        .nav-link-custom i {
            font-size: 1.2rem;
        }

        .nav-link-custom.active {
            border: 2px solid rgba(0, 123, 255, 0.5);
            background: rgba(0, 123, 255, 0.1);
            border-radius: 8px;
        }

        .nav-link-custom:hover {
            color: #007bff;
        }

        /* Autocomplete Styles */
        .autocomplete-container {
            position: relative;
        }

        .suggestions-dropdown {
            position: absolute;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            background: white;
            border: 1px solid #ddd;
            border-radius: 0 0 4px 4px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: none;
        }

        .suggestion-item {
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .suggestion-item:hover, .suggestion-item.selected {
            background-color: #f8f9fa;
        }

        .suggestion-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <!-- Navigation Headers -->
    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
            <a href="<?php echo e(route('inventory.index')); ?>" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="<?php echo e(asset('images/chef-svgrepo-com.svg')); ?>" alt="Chef Hat Logo" style="width: 40px; height: 40px;">
                <h1 class="mb-0" style="font-size: 1.8rem; font-weight: 600; color: inherit;">Cooking Manager</h1>
            </a>

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

    <!-- Main Content -->
    <div class="container mt-5">
        <a href="<?php echo e(route('recipes.feasible')); ?>" class="btn btn-info mb-3">Check Feasible Recipes</a>
        <button id="add-ingredient-button" class="btn btn-primary mb-3">Add Ingredient</button>

        <!-- Add Ingredient Form (Initially Hidden) -->
        <div id="add-ingredient-form" style="display: none;">
            <form action="<?php echo e(route('ingredients.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="form-group autocomplete-container">
                    <label for="name">Ingredient Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                    <div class="suggestions-dropdown" id="new-ingredient-suggestions"></div>
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

        <!-- Inventory Table -->
        <table class="table table-bordered" id="inventory-table">
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
                    <tr data-id="<?php echo e($item->id); ?>">
                        <td><?php echo e($item->ingredient->name); ?></td>
                        <td><?php echo e($item->quantity); ?></td>
                        <td><?php echo e($item->unit); ?></td>
                        <td><?php echo e($item->ingredient->category); ?></td>
                        <td><?php echo e($item->expiration_date); ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-item" data-id="<?php echo e($item->id); ?>">Edit</button>
                            <button class="btn btn-sm btn-danger delete-item" data-id="<?php echo e($item->id); ?>">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <!-- Empty Row for Adding New Items -->
                <tr id="add-item-row">
                    <td>
                        <div class="autocomplete-container">
                            <input type="text" id="ingredient-name" class="form-control" placeholder="Ingredient Name">
                            <div class="suggestions-dropdown" id="ingredient-suggestions"></div>
                        </div>
                    </td>
                    <td><input type="number" id="quantity" class="form-control" placeholder="Quantity"></td>
                    <td><input type="text" id="unit" class="form-control" placeholder="Unit"></td>
                    <td><input type="text" id="ingredient-category" class="form-control" placeholder="Category"></td>
                    <td><input type="date" id="expiration-date" class="form-control"></td>
                    <td><button id="add-item" class="btn btn-success">Add</button></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Edit Form (Initially Hidden) -->
    <div id="edit-form" style="display: none;">
        <form id="edit-item-form">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="form-group autocomplete-container">
                <label for="edit-ingredient-name">Ingredient Name</label>
                <input type="text" name="ingredient_name" id="edit-ingredient-name" class="form-control" required>
                <div class="suggestions-dropdown" id="edit-ingredient-suggestions"></div>
            </div>
            <div class="form-group">
                <label for="edit-quantity">Quantity</label>
                <input type="number" name="quantity" id="edit-quantity" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="edit-unit">Unit</label>
                <select name="unit" id="edit-unit" class="form-control" required>
                    <option value="liters">Liters</option>
                    <option value="grams">Grams</option>
                    <option value="pieces">Pieces</option>
                </select>
            </div>
            <div class="form-group">
                <label for="edit-ingredient-category">Category</label>
                <input type="text" name="category" id="edit-ingredient-category" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="edit-expiration-date">Expiration Date</label>
                <input type="date" name="expiration_date" id="edit-expiration-date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="button" id="cancel-edit" class="btn btn-secondary">Cancel</button>
        </form>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            
            // Hide flash message
            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                setTimeout(() => flashMessage.style.display = 'none', 3000);
            }

            // Toggle add ingredient form
            document.getElementById('add-ingredient-button').addEventListener('click', function() {
                const form = document.getElementById('add-ingredient-form');
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
            });
            
            $('#add-ingredient-form form').on('submit', function(e) {
                e.preventDefault();
                
                const formData = $(this).serialize();
                
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            alert(xhr.responseJSON.error || 'This ingredient already exists');
                        } else {
                            alert('Failed to add ingredient');
                        }
                    }
                });
            });

            // Add New Item
            $('#add-item').click(function() {
                const data = {
                    _token: "<?php echo e(csrf_token()); ?>",
                    ingredient_name: $('#ingredient-name').val(),
                    quantity: $('#quantity').val(),
                    unit: $('#unit').val(),
                    category: $('#ingredient-category').val(),
                    expiration_date: $('#expiration-date').val()
                };

                $.ajax({
                    url: "<?php echo e(route('inventory.store')); ?>",
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        $('#add-item-row').before(`
                            <tr data-id="${response.id}">
                                <td>${response.ingredient.name}</td>
                                <td>${response.quantity}</td>
                                <td>${response.unit}</td>
                                <td>${response.ingredient.category}</td>
                                <td>${response.expiration_date}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-item" data-id="${response.id}">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-item" data-id="${response.id}">Delete</button>
                                </td>
                            </tr>
                        `);
                        $('#ingredient-name, #quantity, #unit, #ingredient-category, #expiration-date').val('');
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            alert(xhr.responseJSON.error || 'This ingredient already exists in your inventory');
                        } else {
                            alert('Error: ' + (xhr.responseJSON.message || 'Operation failed'));
                        }
                    }
                });
            });

            // Autocomplete functionality
            function setupAutocomplete(inputElement, suggestionsElement, categoryElement = null) {
                let selectedIndex = -1;
                let suggestions = [];

                inputElement.on('input', function() {
                    const query = $(this).val();
                    if (query.length < 2) {
                        suggestionsElement.empty().hide();
                        return;
                    }
                    
                    $.ajax({
                        url: "<?php echo e(route('ingredients.search')); ?>",
                        method: 'GET',
                        data: { query: query },
                        success: function(response) {
                            suggestions = response;
                            suggestionsElement.empty();
                            
                            if (response.length === 0) {
                                suggestionsElement.hide();
                                return;
                            }
                            
                            response.forEach((ingredient, index) => {
                                suggestionsElement.append(`
                                    <div class="suggestion-item" 
                                        data-index="${index}"
                                        data-name="${ingredient.name}" 
                                        data-category="${ingredient.category}">
                                        ${ingredient.name}
                                    </div>
                                `);
                            });
                            
                            suggestionsElement.show();
                        }
                    });
                });

                // Handle suggestion click
                suggestionsElement.on('click', '.suggestion-item', function() {
                    const name = $(this).data('name');
                    const category = $(this).data('category');
                    
                    inputElement.val(name);
                    if (categoryElement) {
                        categoryElement.val(category);
                    }
                    suggestionsElement.hide();
                });

                // Keyboard navigation
                inputElement.on('keydown', function(e) {
                    if (!suggestionsElement.is(':visible')) return;

                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        selectedIndex = Math.min(selectedIndex + 1, suggestions.length - 1);
                        updateSelection();
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        selectedIndex = Math.max(selectedIndex - 1, -1);
                        updateSelection();
                    } else if (e.key === 'Enter' && selectedIndex >= 0) {
                        e.preventDefault();
                        const selected = suggestions[selectedIndex];
                        inputElement.val(selected.name);
                        if (categoryElement) {
                            categoryElement.val(selected.category);
                        }
                        suggestionsElement.hide();
                    }
                });

                function updateSelection() {
                    suggestionsElement.find('.suggestion-item').removeClass('selected');
                    if (selectedIndex >= 0) {
                        suggestionsElement.find(`.suggestion-item[data-index="${selectedIndex}"]`).addClass('selected');
                    }
                }

                // Hide suggestions when clicking elsewhere
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.autocomplete-container').length) {
                        suggestionsElement.hide();
                    }
                });
            }

            // Setup autocomplete for add form
            setupAutocomplete(
                $('#ingredient-name'),
                $('#ingredient-suggestions'),
                $('#ingredient-category')
            );

            // Autocomplete for new ingredient form
            setupAutocomplete(
                $('#name'), // The ingredient name input field
                $('#new-ingredient-suggestions'), // New suggestions dropdown container
                $('#category') // The category input field to auto-fill
            );

            // Setup autocomplete for edit form
            setupAutocomplete(
                $('#edit-ingredient-name'),
                $('#edit-ingredient-suggestions'),
                $('#edit-ingredient-category')
            );

            // Delete Item
            $(document).on('click', '.delete-item', function() {
                const itemId = $(this).data('id');
                if (confirm('Are you sure you want to remove this from your inventory?')) {
                    $.ajax({
                        url: `/inventory/${itemId}`,
                        method: 'DELETE',
                        data: { _token: "<?php echo e(csrf_token()); ?>" },
                        success: function() {
                            $(`tr[data-id="${itemId}"]`).remove();
                        },
                        error: function(xhr) {
                            alert('Error: ' + (xhr.responseJSON.message || 'Failed to delete item'));
                        }
                    });
                }
            });

            // Edit Item functionality
            let editingItemId = null;

            $(document).on('click', '.edit-item', function() {
                editingItemId = $(this).data('id');
                
                $.ajax({
                    url: `/inventory/${editingItemId}/edit`,
                    method: 'GET',
                    success: function(response) {
                        $('#edit-ingredient-name').val(response.ingredient.name);
                        $('#edit-quantity').val(response.quantity);
                        $('#edit-unit').val(response.unit);
                        $('#edit-ingredient-category').val(response.ingredient.category);
                        $('#edit-expiration-date').val(response.expiration_date);
                        $('#edit-form').show();
                    },
                    error: function(xhr) {
                        console.error('Error fetching item:', xhr);
                        alert('Failed to load item for editing');
                    }
                });
            });

            $('#edit-item-form').on('submit', function(e) {
                e.preventDefault();
                
                const formData = {
                    _token: "<?php echo e(csrf_token()); ?>",
                    _method: 'PUT',
                    ingredient_name: $('#edit-ingredient-name').val(),
                    quantity: $('#edit-quantity').val(),
                    unit: $('#edit-unit').val(),
                    category: $('#edit-ingredient-category').val(),
                    expiration_date: $('#edit-expiration-date').val()
                };
                
                $.ajax({
                    url: `/inventory/${editingItemId}`,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $(`tr[data-id="${editingItemId}"]`).html(`
                            <td>${response.ingredient.name}</td>
                            <td>${response.quantity}</td>
                            <td>${response.unit}</td>
                            <td>${response.ingredient.category}</td>
                            <td>${response.expiration_date}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-item" data-id="${response.id}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-item" data-id="${response.id}">Delete</button>
                            </td>
                        `);
                        $('#edit-form').hide();
                    },
                    error: function(xhr) {
                        console.error('Error updating item:', xhr);
                        alert('Error updating item: ' + (xhr.responseJSON.message || 'Please check the form data'));
                    }
                });
            });

            // Cancel Edit
            $('#cancel-edit').click(function() {
                $('#edit-form').hide();
            });
        });
    </script>
</body>
</html><?php /**PATH C:\Users\steph\ingredient-tracker\resources\views/inventory/index.blade.php ENDPATH**/ ?>