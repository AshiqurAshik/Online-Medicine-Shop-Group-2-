<?php
$user   = $_SESSION['user'];
$isEdit = isset($editing) && !empty($editing);
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

 <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --success: #10b981;
            --danger: #ef4444;
            --danger-hover: #dc2626;
            --warning: #f59e0b;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background: var(--bg-body);
            color: var(--text-main);
            line-height: 1.5;
        }

        .navbar {
            background: #ffffff;
            padding: 0.75rem 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-inner {
            width: 90%;
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -0.025em;
        }

        .nav-links {
            display: flex;
            gap: 0.5rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-muted);
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .nav-links a:hover {
            background: #eff6ff;
            color: var(--primary);
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .welcome {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .welcome b {
            color: var(--text-main);
        }

        .logout-btn {
            background: #fee2e2;
            color: var(--danger);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background: var(--danger);
            color: white;
        }

        .main-content {
            width: 90%;
            max-width: 1400px;
            margin: 2rem auto;
        }

        h1 {
            font-size: 1.875rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: var(--text-main);
        }

        h2 {
            font-size: 1.25rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease;
        }

        .stat-card:hover { transform: translateY(-4px); }

        .stat-card h3 {
            font-size: 0.875rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .stat-card .value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
        }

        .card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            margin-bottom: 2rem;
        }

        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        .form-full { grid-column: span 2; }

        input, select, textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s;
            background: #fcfcfd;
        }

        textarea { min-height: 100px; resize: vertical; }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            background: white;
        }

        .btn-submit {
            grid-column: span 2;
            background: var(--primary);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        table th {
            background: #f1f5f9;
            color: var(--text-muted);
            padding: 1rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.875rem;
        }

        table tr:last-child td { border-bottom: none; }
        table tr:hover td { background-color: #f8fafc; }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-stock { background: #dcfce7; color: #166534; }
        .badge-status { background: #fef3c7; color: #92400e; }
        .badge-info { background: #e0f2fe; color: #0369a1; }

        .action-group {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .btn-action {
            text-decoration: none;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid transparent;
        }

        .btn-edit {
            background: #eff6ff;
            color: var(--primary);
            border-color: #dbeafe;
        }

        .btn-edit:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .btn-delete {
            background: #fef2f2;
            color: var(--danger);
            border-color: #fee2e2;
        }

        .btn-delete:hover {
            background: var(--danger);
            color: white;
            border-color: var(--danger);
        }

        
    </style>

</head>

<body>

<header class="navbar">

    <div class="navbar-inner">

       
        <div class="logo">
            💊 Online Medicine Shop
        </div>

       
        <nav class="nav-links">

            <a href="index.php?page=admin">🏠 Dashboard</a>

            <a href="index.php?page=categories">📂 Categories</a>

            <a href="index.php?page=manageCustomer">👥 Customers</a>

            <a href="index.php?page=orders">📦 Orders</a>

            <a href="index.php?page=history">📜 History</a>

            <a href="index.php?page=profile">👤 Profile</a>

        </nav>

        <!-- USER -->
        <div class="nav-user">

            <span class="welcome">
                Welcome,
                <b><?= htmlspecialchars($user['name']) ?></b>
            </span>

            <a href="index.php?page=logout" class="logout-btn">
                🚪 Logout
            </a>

        </div>

    </div>

</header>

<main class="main-content">

    <h1>Admin Dashboard</h1>


    <div class="dashboard-grid">

        <div class="stat-card">
            <h3>Total Medicines</h3>
            <p class="value"><?= count($medicines ?? []) ?></p>
        </div>

        <div class="stat-card">
            <h3>Total Categories</h3>
            <p class="value"><?= count($categories ?? []) ?></p>
        </div>

        <div class="stat-card">
            <h3>Total Orders</h3>
            <p class="value"><?= count($orders ?? []) ?></p>
        </div>

    </div>

    <div class="card">

        <h2>
            <span>📝</span> <?= $isEdit ? 'Edit Medicine' : 'Add Medicine' ?>
        </h2>

        <form method="POST"
              enctype="multipart/form-data"
              action="index.php?page=admin&action=<?= $isEdit ? 'updateMedicine' : 'addMedicine' ?>">

            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= intval($editing['id']) ?>">
            <?php endif; ?>

            <input type="text"
                   name="name"
                   placeholder="Medicine Name"
                   value="<?= htmlspecialchars($editing['name'] ?? '') ?>"
                   required>

            <select name="category_id" required>

                <option value="">Select Category</option>

                <?php foreach ($categories ?? [] as $cat): ?>

                    <option value="<?= intval($cat['id']) ?>"
                        <?= ($isEdit && $editing['category_id'] == $cat['id']) ? 'selected' : '' ?>>

                        <?= htmlspecialchars($cat['name']) ?>
                        (<?= htmlspecialchars($cat['category_type']) ?>)

                    </option>

                <?php endforeach; ?>

            </select>

            <input type="text"
                   name="vendor_name"
                   placeholder="Vendor Name"
                   value="<?= htmlspecialchars($editing['vendor_name'] ?? '') ?>"
                   required>

            <input type="number"
                   step="0.01"
                   name="price"
                   placeholder="Price"
                   value="<?= htmlspecialchars($editing['price'] ?? '') ?>"
                   required>

            <input type="number"
                   name="availability"
                   placeholder="Stock"
                   value="<?= htmlspecialchars($editing['availability'] ?? '') ?>"
                   required>

            <div class="form-full">
                <textarea name="description"
                          placeholder="Description"><?= htmlspecialchars($editing['description'] ?? '') ?></textarea>
            </div>

            <div class="form-full">
                <label style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.5rem; display: block;">Medicine Image</label>
                <input type="file"
                       name="image"
                       accept="image/*"
                       <?= $isEdit ? '' : 'required' ?>>
            </div>

            <button type="submit" class="btn-submit">
                <?= $isEdit ? 'Update Medicine' : 'Add Medicine' ?>
            </button>

        </form>

    </div>

    <div class="card">

        <h2><span>📋</span> Medicine List</h2>

        <div class="table-container">
            <table>

                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Vendor</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>

                <?php foreach ($medicines ?? [] as $m): ?>

                <tr>

                    <td><span class="badge badge-info">#<?= intval($m['id']) ?></span></td>

                    <td><strong><?= htmlspecialchars($m['name']) ?></strong></td>

                    <td><?= htmlspecialchars($m['category_name'] ?? 'N/A') ?></td>

                    <td><?= htmlspecialchars($m['vendor_name']) ?></td>

                    <td>
                        <span style="font-weight: 700; color: var(--primary);">৳<?= number_format($m['price'],2) ?></span>
                    </td>

                    <td>
                        <span class="badge badge-stock"><?= intval($m['availability']) ?> in stock</span>
                    </td>

                    <td>
                        <div class="action-group">
                            <a href="index.php?page=admin&action=editMedicine&id=<?= intval($m['id']) ?>" class="btn-action btn-edit">
                                Edit
                            </a>

                            <span style="color: var(--border-color)">|</span>

                            <a href="index.php?page=admin&action=deleteMedicine&id=<?= intval($m['id']) ?>"
                               class="btn-action btn-delete"
                               onclick="return confirm('Delete medicine?')">
                                Delete
                            </a>
                        </div>
                    </td>

                </tr>

                <?php endforeach; ?>

            </table>
        </div>

    </div>

    <div class="card">

        <h2><span>🛒</span> Recent Orders</h2>

        <div class="table-container">
            <table>

                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>

                <?php foreach ($orders ?? [] as $o): ?>

                <tr>

                    <td>#<?= intval($o['id']) ?></td>

                    <td><?= htmlspecialchars($o['name']) ?></td>

                    <td>
                        <strong>৳<?= number_format($o['total_amount'],2) ?></strong>
                    </td>

                    <td><span class="badge badge-status"><?= htmlspecialchars($o['status']) ?></span></td>

                </tr>

                <?php endforeach; ?>

            </table>
        </div>

    </div>

</main>

</body>
</html>