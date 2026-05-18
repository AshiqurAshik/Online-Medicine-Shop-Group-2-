<?php

$user     = $_SESSION['user'] ?? ['name' => 'Admin'];
$isAdmin  = ($user['role'] ?? '') === 'admin';
$userId   = $user['id'] ?? 0;

$orders = array_values(array_filter($orders ?? [], function ($o) use ($isAdmin, $userId) {
    $statusOk = ($o['status'] ?? '') === 'accepted';
    if (!$isAdmin) {
        return $statusOk && isset($o['user_id']) && (int)$o['user_id'] === (int)$userId;
    }
    return $statusOk;
}));

$grandTotal = array_sum(array_column($orders, 'total_amount'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Purchase History — Online Medicine Shop</title>
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
            --warning: #f59e0b;
            --gold: #f59e0b;
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
            max-width: 1400px;
            margin: 2rem auto;
        }

        h1 { font-size: 1.875rem; font-weight: 800; color: var(--text-main); }
        .page-sub { color: var(--text-muted); font-size: .95rem; margin-top: .35rem; }
        .top-row { display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem; margin-bottom:2rem; }

        .chip { display:inline-block; padding:.45rem 1rem; border-radius:999px; font-weight:700; font-size:.8rem; }
        .chip-ok { background:#dcfce7; color:#166534; }

        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); 
            gap: 1.5rem; 
            margin-bottom: 2.5rem; 
        }

        .stat-tile { 
            background: #fff; 
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg); 
            box-shadow: var(--shadow);
            padding: 1.5rem; 
            display: flex; 
            align-items: center; 
            gap: 1.25rem; 
            transition: transform 0.2s ease;
        }
        .stat-tile:hover { transform: translateY(-3px); }

        .stat-ico { 
            width: 54px; 
            height: 54px; 
            border-radius: 14px; 
            display: flex; 
            align-items: center;
            justify-content: center; 
            font-size: 1.5rem; 
            flex-shrink: 0; 
            color: #fff;
            background: linear-gradient(135deg, var(--primary), #3b82f6); 
        }
        .stat-ico.gold { background: linear-gradient(135deg, var(--gold), #d97706); }
        
        .stat-num { font-size: 1.5rem; font-weight: 900; color: var(--text-main); letter-spacing: -0.02em; }
        .stat-cap { font-size: .75rem; text-transform: uppercase; letter-spacing: .05em; color: var(--text-muted); font-weight: 700; }

        .card {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            margin-bottom: 2rem;
        }

        .order-head { 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start;
            flex-wrap: wrap; 
            gap: 1rem; 
            padding-bottom: 1.5rem; 
            border-bottom: 1px solid var(--border-color); 
            margin-bottom: 1.5rem; 
        }

        .order-id { font-weight: 800; font-size: 1.15rem; color: var(--text-main); margin-bottom: .4rem; }
        .order-meta { font-size: .875rem; color: var(--text-muted); line-height: 1.7; }
        .order-total { font-size: 1.75rem; font-weight: 900; color: var(--primary); letter-spacing: -0.025em; }

        .badge-ok { 
            background: #dcfce7; 
            color: #166534; 
            padding: .3rem .75rem;
            border-radius: 999px; 
            font-size: .7rem; 
            font-weight: 700; 
            margin-left: .6rem; 
            vertical-align: middle; 
            text-transform: uppercase;
        }

        .items-tbl { width: 100%; border-collapse: collapse; font-size: .9rem; margin-top: 0.5rem; }
        .items-tbl th { 
            background: #f8fafc; 
            border: none; 
            border-bottom: 2px solid var(--border-color);
            padding: 0.75rem 1rem; 
            font-size: .7rem; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
            color: var(--text-muted); 
            font-weight: 800;
        }
        .items-tbl td { border: none; border-bottom: 1px solid var(--border-color); padding: 1rem; }
        .items-tbl tr:last-child td { border-bottom: none; }
        .items-tbl tr:hover td { background: #fcfdfe; }

        .empty-card { text-align: center; color: var(--text-muted); padding: 4rem; font-weight: 500; font-size: 1.1rem; }

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

    <div class="top-row">
        <div>
            <h1>Purchase History</h1>
            <p class="page-sub">
                <?= $isAdmin
                    ? 'All accepted orders across all customers'
                    : 'Your completed orders' ?>
            </p>
        </div>
        <span class="chip chip-ok">✓ <?= count($orders) ?> completed</span>
    </div>

    <?php if (empty($orders)): ?>
        <div class="card empty-card">No accepted orders yet.</div>
    <?php else: ?>

        <div class="stats-grid">
            <div class="stat-tile">
                <div class="stat-ico">📋</div>
                <div>
                    <div class="stat-num"><?= count($orders) ?></div>
                    <div class="stat-cap">Completed Orders</div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-ico gold">💰</div>
                <div>
                    <div class="stat-num" style="font-size:1.35rem">৳<?= number_format($grandTotal, 2) ?></div>
                    <div class="stat-cap"><?= $isAdmin ? 'Total Revenue' : 'Total Spent' ?></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-ico">📈</div>
                <div>
                    <div class="stat-num" style="font-size:1.35rem">
                        ৳<?= count($orders) ? number_format($grandTotal / count($orders), 2) : '0.00' ?>
                    </div>
                    <div class="stat-cap">Avg. Order Value</div>
                </div>
            </div>
        </div>

        <?php foreach ($orders as $order): ?>
        <?php
            $custName  = $order['customer_name'] ?? $order['name'] ?? 'Customer';
            $custEmail = $order['customer_email'] ?? '';
            $items     = $order['items'] ?? [];
        ?>
        <div class="card">
            <div class="order-head">
                <div>
                    <div class="order-id">
                        Order #<?= intval($order['id']) ?>
                        <span class="badge-ok">Accepted</span>
                    </div>
                    <div class="order-meta">
                        <?php if ($isAdmin): ?>
                            👤 <b><?= htmlspecialchars($custName) ?></b>
                            <?= $custEmail ? ' &nbsp;•&nbsp; ' . htmlspecialchars($custEmail) : '' ?><br>
                        <?php endif; ?>
                        📅 <?= date('d M Y, h:i A', strtotime($order['order_date'])) ?>
                        &nbsp;•&nbsp; 📍 <?= htmlspecialchars($order['shipping_address'] ?? '—') ?>
                        &nbsp;•&nbsp; 💳 <?= htmlspecialchars($order['payment_method'] ?? '—') ?>
                    </div>
                </div>
                <div class="order-total">৳<?= number_format($order['total_amount'], 2) ?></div>
            </div>

            <?php if (!empty($items)): ?>
            <table class="items-tbl">
                <thead>
                    <tr>
                        <th style="text-align:left">Medicine</th>
                        <th style="text-align:left">Vendor</th>
                        <th style="text-align:center">Qty</th>
                        <th style="text-align:right">Unit Price</th>
                        <th style="text-align:right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td style="font-weight:600;color:var(--text-main)"><?= htmlspecialchars($item['medicine_name'] ?? '') ?></td>
                        <td style="color:var(--text-muted)"><?= htmlspecialchars($item['vendor_name'] ?? '—') ?></td>
                        <td style="text-align:center"><b><?= intval($item['quantity'] ?? 0) ?></b></td>
                        <td style="text-align:right">৳<?= number_format($item['unit_price'] ?? 0, 2) ?></td>
                        <td style="text-align:right;font-weight:800;color:var(--primary)">
                            ৳<?= number_format(($item['unit_price'] ?? 0) * ($item['quantity'] ?? 0), 2) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="color:var(--text-muted);font-size:.88rem;margin:0; font-style: italic;">Item breakdown not available for this order.</p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

    <?php endif; ?>

</main>

</body>
</html>