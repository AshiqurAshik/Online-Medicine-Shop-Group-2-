<?php
$user = $_SESSION['user'];
$isEdit = isset($editing) && !empty($editing);
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="admin.css">
</head>

<body>

<!-- ================= NAVBAR ================= -->

<header class="navbar">
    <div class="navbar-inner">

        <h2>Online Medicine Shop</h2>

        <div>
            <span>
                Welcome, <?= htmlspecialchars($user['name']) ?>
            </span>

            <a href="index.php?page=logout">Logout</a>
        </div>

    </div>
</header>


<!-- ================= MAIN ================= -->

<main class="main-content">

    <h1>Admin Dashboard</h1>

    <!-- ================= STATS ================= -->

    <div class="dashboard-grid">

        <div class="card">
            <h3>Total Medicines</h3>
            <p><?= count($medicines ?? []) ?></p>
        </div>

        <div class="card">
            <h3>Total Categories</h3>
            <p><?= count($categories ?? []) ?></p>
        </div>

        <div class="card">
            <h3>Total Orders</h3>
            <p><?= count($orders ?? []) ?></p>
        </div>

    </div>


    <!-- ================= ADD / EDIT MEDICINE ================= -->

    <div class="card">

        <h2>
            <?= $isEdit ? "Edit Medicine" : "Add Medicine" ?>
        </h2>

        <form method="POST"
              enctype="multipart/form-data"
              action="index.php?page=admin&action=<?= $isEdit ? 'updateMedicine&id=' . $editing['id'] : 'addMedicine' ?>">

            <input type="text" name="name"
                   placeholder="Medicine Name"
                   value="<?= $editing['name'] ?? '' ?>"
                   required>

            <select name="category_id" required>
                <option value="">Select Category</option>

                <?php foreach ($categories ?? [] as $cat): ?>
                    <option value="<?= $cat['id'] ?>"
                        <?= ($isEdit && $editing['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                        (<?= htmlspecialchars($cat['category_type']) ?>)
                    </option>
                <?php endforeach; ?>

            </select>

            <input type="text" name="vendor_name"
                   placeholder="Vendor Name"
                   value="<?= $editing['vendor_name'] ?? '' ?>"
                   required>

            <input type="number" step="0.01" name="price"
                   placeholder="Price"
                   value="<?= $editing['price'] ?? '' ?>"
                   required>

            <input type="number" name="availability"
                   placeholder="Stock"
                   value="<?= $editing['availability'] ?? '' ?>"
                   required>

            <textarea name="description"
                      placeholder="Description"><?= $editing['description'] ?? '' ?></textarea>

            <input type="file" name="image" <?= $isEdit ? '' : 'required' ?>>

            <button type="submit">
                <?= $isEdit ? "Update Medicine" : "Add Medicine" ?>
            </button>

        </form>

    </div>


    <!-- ================= MEDICINE TABLE ================= -->

    <div class="card">

        <h2>Medicine List</h2>

        <table border="1" width="100%">

            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Vendor</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>

            <?php foreach ($medicines ?? [] as $m): ?>

                <tr>

                    <td><?= $m['id'] ?></td>
                    <td><?= htmlspecialchars($m['name']) ?></td>
                    <td><?= htmlspecialchars($m['vendor_name']) ?></td>
                    <td><?= $m['price'] ?></td>
                    <td><?= $m['availability'] ?></td>

                    <td>

                        <a href="index.php?page=admin&action=editMedicine&id=<?= $m['id'] ?>">
                            Edit
                        </a>

                        |

                        <a href="index.php?page=admin&action=deleteMedicine&id=<?= $m['id'] ?>"
                           onclick="return confirm('Delete medicine?')">
                            Delete
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        </table>

    </div>


    <!-- ================= ORDERS ================= -->

    <div class="card">

        <h2>Orders</h2>

        <table border="1" width="100%">

            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
            </tr>

            <?php foreach ($orders ?? [] as $o): ?>

                <tr>
                    <td><?= $o['id'] ?></td>
                    <td><?= htmlspecialchars($o['name']) ?></td>
                    <td><?= $o['total_amount'] ?></td>
                    <td><?= htmlspecialchars($o['status']) ?></td>
                </tr>

            <?php endforeach; ?>

        </table>

    </div>

</main>

</body>
</html>