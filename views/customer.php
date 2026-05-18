<?php
$user   = $_SESSION['user'];
$userId = $user['id'];


$myOrders = array_values(array_filter($orders ?? [], function ($o) use ($userId) {
    return isset($o['user_id']) && (int)$o['user_id'] === (int)$userId;
}));


$cart = getCartItems($conn, $userId);
$cartTotal = 0;


$selectedCategory = $_GET['category'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Dashboard</title>

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
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --radius-md: 12px;
            --radius-lg: 16px;
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
            max-width: 1200px;
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
            flex: 0 0 auto;
        }

        .nav-links {
            display: flex;
            gap: 0.5rem;
            flex: 1;
            justify-content: center;
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 0 0 auto;
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

        .welcome b { color: var(--text-main); }

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

        .logout-btn:hover { background: var(--danger); color: white; }

        .main-content {
            width: 90%;
            max-width: 1200px;
            margin: 2rem auto;
        }

        h1 { font-size: 1.875rem; font-weight: 800; color: var(--text-main); margin-bottom: 2rem;}
        h3 { font-size: 1.15rem; font-weight: 700; margin-bottom: 1.25rem; color: var(--text-main); }

        .top-controls {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .top-controls .card {
            flex: 1;
            min-width: 280px;
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            background: #fff;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow);
            transition: transform 0.25s ease;
        }

        .top-controls .card:hover { transform: translateY(-3px); }

        .search-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: #fcfdfe;
            transition: 0.2s ease;
        }

        .search-wrapper:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background: #fff;
        }

        #searchBox {
            width: 100%;
            border: none;
            outline: none;
            font-size: 15px;
            background: transparent;
            color: var(--text-main);
        }

        .select-wrapper select {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            outline: none;
            font-size: 14px;
            background: #fcfdfe;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .select-wrapper select:focus {
            border-color: var(--primary);
            background: #fff;
        }

        .card {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            margin-bottom: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        table th {
            background: #f8fafc;
            color: var(--text-muted);
            padding: 1rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 0.05em;
            border-bottom: 2px solid var(--border-color);
        }

        table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.875rem;
        }

        table tr:last-child td { border-bottom: none; }
        table tr:hover td { background-color: #fcfdfe; }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 99px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-stock { background: #dcfce7; color: #166534; }
        .badge-out { background: #fee2e2; color: #991b1b; }
        .badge-status { background: #fef3c7; color: #92400e; }

        .btn-cart {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-cart:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }

        input[type="number"] {
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            outline: none;
            text-align: center;
        }

        
</style>

</head>

<body>


<header class="navbar">
    <div class="navbar-inner">

        <!-- LOGO -->
        <div class="logo">
            💊 Online Medicine Shop
        </div>

        <!-- NAVIGATION -->
        <nav class="nav-links">
            <a href="index.php?page=customer">🏠 Dashboard</a>
            <a href="index.php?page=history">📋 My History</a>
            <a href="index.php?page=profile">👤 Profile</a>
        </nav>

        <!-- USER -->
        <div class="nav-user">
            <span class="welcome">
                Welcome, <b><?= htmlspecialchars($user['name']) ?></b>
            </span>
            <a href="index.php?page=logout" class="logout-btn">🚪 Logout</a>
        </div>

    </div>
</header>


<main class="main-content">

    <h1>Customer Dashboard</h1>

    <div class="top-controls">

        <div class="card search-card">

            <h3>Search Medicines</h3>

            <div class="search-wrapper">

                <span class="search-icon">🔍</span>

                <input type="text"
                       placeholder="Search medicine, vendor..."
                       id="searchBox"
                       oninput="filterMedicines(this.value)">

            </div>

        </div>

        <div class="card filter-card">

            <h3>Filter by Category</h3>

            <form method="GET" action="index.php" class="filter-form">

                <input type="hidden" name="page" value="customer">

                <div class="select-wrapper">

                    <select name="category" onchange="this.form.submit()">

                        <option value="0">All Categories</option>

                        <?php foreach ($categories ?? [] as $cat): ?>

                            <option value="<?= intval($cat['id']) ?>"
                                <?= ($selectedCategory == $cat['id']) ? 'selected' : '' ?>>

                                <?= htmlspecialchars($cat['name']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

            </form>

        </div>

    </div>


    <div class="card">

        <h3>Available Medicines</h3>

        <div style="overflow-x: auto;">
            <table id="medicineTable">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Vendor</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($medicines ?? [] as $m): ?>

                    <tr class="med-row"
                        data-name="<?= strtolower(htmlspecialchars($m['name'])) ?>"
                        data-vendor="<?= strtolower(htmlspecialchars($m['vendor_name'])) ?>">

                        <td><strong style="color:var(--text-main)"><?= htmlspecialchars($m['name']) ?></strong></td>
                        <td><?= htmlspecialchars($m['category_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($m['vendor_name']) ?></td>
                        <td><span style="font-weight:800;color:var(--primary)">৳<?= number_format($m['price'], 2) ?></span></td>
                        <td>
                            <?php if ($m['availability'] > 0): ?>
                                <span class="badge badge-stock"><?= intval($m['availability']) ?> in stock</span>
                            <?php else: ?>
                                <span class="badge badge-out">Out of Stock</span>
                            <?php endif; ?>
                        </td>

                        <td>

                            <?php if ($m['availability'] > 0): ?>

                                <form method="POST"
                                      action="index.php?page=customer&action=addToCart"
                                      style="display:flex; align-items:center; gap:8px;">

                                    <input type="hidden"
                                           name="medicine_id"
                                           value="<?= intval($m['id']) ?>">

                                    <input type="number"
                                           name="quantity"
                                           value="1"
                                           min="1"
                                           max="<?= intval($m['availability']) ?>"
                                           style="width:55px;">

                                    <button type="submit" class="btn-cart">Add To Cart</button>

                                </form>

                            <?php else: ?>
                                <span style="color:var(--danger); font-size: 0.8rem; font-weight: 700;">
                                    Currently Unavailable
                                </span>
                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>
        </div>

    </div>


    <div class="card">

        <h3>My Orders</h3>

        <?php if (empty($myOrders)): ?>
            <p style="color:var(--text-muted); font-style: italic;">You have no orders yet.</p>
        <?php else: ?>

        <div style="overflow-x: auto;">
            <table>

                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($myOrders as $o): ?>
                    <tr>
                        <td><span style="font-weight:700">#<?= intval($o['id']) ?></span></td>
                        <td style="font-weight:800;color:var(--primary)">৳<?= number_format($o['total_amount'], 2) ?></td>
                        <td><?= htmlspecialchars($o['payment_method'] ?? '—') ?></td>
                        <td style="color:var(--text-muted)"><?= date('d M Y', strtotime($o['order_date'])) ?></td>
                        <td>
                            <span class="badge badge-status">
                                <?= htmlspecialchars($o['status']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>

            </table>
        </div>

        <?php endif; ?>

    </div>

</main>


<script>
function filterMedicines(q) {

    q = q.toLowerCase().trim();

    document.querySelectorAll('.med-row').forEach(row => {

        let name = row.dataset.name || '';
        let vendor = row.dataset.vendor || '';

        row.style.display =
            (!q || name.includes(q) || vendor.includes(q))
            ? ''
            : 'none';
    });
}
</script>

</body>
</html>