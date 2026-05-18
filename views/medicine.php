<?php

$user   = $_SESSION['user'];
$isEdit = !empty($editing);
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Medicine Dashboard - Online Medicine Shop</title>
<link rel="stylesheet" href="admin.css">
</head>

<body class="app-body">


<header class="navbar">

    <div class="navbar-inner">

        <a class="brand" href="index.php?page=admin">
            <span class="brand-icon">&#128138;</span>
            <span>MediCare</span>
        </a>

        <div class="nav-user">

            <span class="user-pill">

                <span class="user-avatar">
                    <?= strtoupper(substr(htmlspecialchars($user['name']), 0, 1)) ?>
                </span>

                <span class="user-meta">

                    <span class="user-name">
                        <?= htmlspecialchars($user['name']) ?>
                    </span>

                    <span class="user-role">
                        Admin
                    </span>

                </span>

            </span>

            <a href="index.php?page=logout" class="btn-logout">
                Logout
            </a>

        </div>

    </div>

</header>



<main class="main-content">

    <div class="page-header">
        <h1>Manage Medicines</h1>
        <p>Add, edit, search and remove medicines</p>
    </div>


    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>


    <div class="card form-card">

        <h3>
            <?= $isEdit ? 'Edit Medicine' : 'Add New Medicine' ?>
        </h3>

        <form method="POST"
              enctype="multipart/form-data"
              action="index.php?page=admin&action=<?= $isEdit ? 'updateMedicine' : 'addMedicine' ?>">

            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= intval($editing['id']) ?>">
            <?php endif; ?>

            <input type="text" name="name" placeholder="Medicine Name"
                   value="<?= htmlspecialchars($editing['name'] ?? '') ?>" required>

        
            <select name="category_id" required>

                <option value="">Select Category</option>

                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>

                        <option value="<?= intval($cat['id']) ?>"
                            <?= (!empty($editing) && $editing['category_id'] == $cat['id']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($cat['name']) ?>
                            (<?= htmlspecialchars($cat['category_type']) ?>)
                        </option>

                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>No categories found</option>
                <?php endif; ?>

            </select>

            <input type="text" name="vendor_name" placeholder="Vendor Name"
                   value="<?= htmlspecialchars($editing['vendor_name'] ?? '') ?>" required>

            <input type="number" step="0.01" name="price" placeholder="Price"
                   value="<?= htmlspecialchars($editing['price'] ?? '') ?>" required>

            <input type="number" name="availability" placeholder="Stock"
                   value="<?= htmlspecialchars($editing['availability'] ?? '') ?>" required>

            <textarea name="description" placeholder="Description"><?= htmlspecialchars($editing['description'] ?? '') ?></textarea>

            <?php if (!$isEdit): ?>
                <input type="file" name="image" accept="image/*" required>
            <?php endif; ?>

            <button type="submit">
                <?= $isEdit ? 'Update Medicine' : 'Add Medicine' ?>
            </button>

        </form>

    </div>


    <div class="card">

        <h3>Medicine List</h3>

        <table border="1" width="100%">

            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Vendor</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($medicines)): ?>

                    <?php foreach ($medicines as $i => $medicine): ?>

                        <tr>

                            <td><?= $i + 1 ?></td>

                            <td><?= htmlspecialchars($medicine['name']) ?></td>

                            <td>
                                <?= htmlspecialchars($medicine['category_name'] ?? 'N/A') ?>
                            </td>

                            <td><?= htmlspecialchars($medicine['vendor_name']) ?></td>

                            <td>৳<?= number_format($medicine['price'], 2) ?></td>

                            <td><?= intval($medicine['availability']) ?></td>

                            <td>
                                
                                <a href="index.php?page=admin&action=editMedicine&id=<?= intval($medicine['id']) ?>">
                                    Edit
                                </a>

                                &nbsp;|&nbsp;

                                <a href="index.php?page=admin&action=deleteMedicine&id=<?= intval($medicine['id']) ?>"
                                   onclick="return confirm('Delete this medicine?')">
                                    Delete
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7" style="text-align:center;color:#6b7280;padding:2rem">
                            No medicines found
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</main>

</body>
</html>