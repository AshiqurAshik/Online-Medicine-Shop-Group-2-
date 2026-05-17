<?php
$user = $_SESSION['user'];
$isEdit = !empty($editing);
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Medicine Dashboard - Online Medicine Shop</title>
<link rel="stylesheet" href="style.css">
</head>

<body class="app-body">

<!-- ================= NAVBAR ================= -->

<header class="navbar">

    <div class="navbar-inner">

        <a class="brand" href="index.php?page=medicine">
            <span class="brand-icon">&#128138;</span>
            <span>MediCare</span>
        </a>

        <div class="nav-user">

            <span class="user-pill">

                <span class="user-avatar">
                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
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


<!-- ================= MAIN CONTENT ================= -->

<main class="main-content">

    <div class="page-header">
        <h1>Manage Medicines</h1>
        <p>Add, edit, search and remove medicines</p>
    </div>

    <!-- ================= MESSAGE ================= -->

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- ================= FORM ================= -->

    <div class="card form-card">

        <h3>
            <?= $isEdit
                ? 'Edit Medicine'
                : 'Add New Medicine'
            ?>
        </h3>

        <form method="POST"
              enctype="multipart/form-data"
              action="index.php?page=medicine&action=<?= $isEdit ? 'update&id=' . intval($editing['id']) : 'add' ?>">

            <input type="text" name="name" placeholder="Medicine Name"
                   value="<?= htmlspecialchars($editing['name'] ?? '') ?>" required>

            <!-- CATEGORY DROPDOWN -->
            <select name="category_id" required>

                <option value="">Select Category</option>

                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>

                        <option value="<?= $cat['id'] ?>"
                            <?= (!empty($editing) && $editing['category_id'] == $cat['id']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($cat['name']) ?>
                            (<?= htmlspecialchars($cat['category_type']) ?>)
                        </option>

                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No categories found</option>
                <?php endif; ?>

            </select>

            <input type="text" name="vendor_name" placeholder="Vendor Name"
                   value="<?= htmlspecialchars($editing['vendor_name'] ?? '') ?>" required>

            <input type="number" step="0.01" name="price" placeholder="Price"
                   value="<?= htmlspecialchars($editing['price'] ?? '') ?>" required>

            <input type="number" name="availability" placeholder="Stock"
                   value="<?= htmlspecialchars($editing['availability'] ?? '') ?>" required>

            <textarea name="description" placeholder="Description">
                <?= htmlspecialchars($editing['description'] ?? '') ?>
            </textarea>

            <?php if (!$isEdit): ?>
                <input type="file" name="image" required>
            <?php endif; ?>

            <button type="submit">
                <?= $isEdit ? 'Update Medicine' : 'Add Medicine' ?>
            </button>

        </form>

    </div>


    <!-- ================= TABLE ================= -->

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

                            <td>$<?= number_format($medicine['price'], 2) ?></td>

                            <td><?= htmlspecialchars($medicine['availability']) ?></td>

                            <td>

                                <a href="index.php?page=medicine&action=edit&id=<?= $medicine['id'] ?>">
                                    Edit
                                </a>

                                <a href="index.php?page=medicine&action=delete&id=<?= $medicine['id'] ?>"
                                   onclick="return confirm('Delete this medicine?')">
                                    Delete
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7">No medicines found</td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</main>

</body>
</html>