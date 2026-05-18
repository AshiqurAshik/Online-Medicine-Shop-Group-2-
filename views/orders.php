<?php

$user   = $_SESSION['user'] ?? ['name' => 'Admin'];
$orders = $orders ?? [];

if (($_SESSION['user']['role'] ?? '') !== 'admin') {
    header('Location: index.php?page=login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Orders — Online Medicine Shop</title>
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
            --radius-md: 16px;
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

        .page-sub { color: var(--text-muted); font-size: .95rem; margin-top: .35rem; }
        .top-row { display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem; margin-bottom:2rem; }
        h1 { font-size: 1.875rem; font-weight: 800; color: var(--text-main); }

        .chip { 
            display:inline-block; padding:.45rem 1rem; border-radius:999px; font-weight:700;
            font-size:.8rem; background:#eff6ff; color:var(--primary); 
        }

        .tabs { display:flex; gap:.6rem; margin-bottom:2rem; flex-wrap:wrap; }
        .tab {
            border:1px solid var(--border-color); background:#fff; color:var(--text-muted);
            padding:.6rem 1.3rem; border-radius:999px; font-weight:700; font-size:.85rem;
            cursor:pointer; transition:all .2s ease;
        }
        .tab:hover { border-color:var(--primary); color:var(--primary); }
        .tab.active { background:var(--primary); color:#fff; border-color:var(--primary); }

        .card {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease;
        }
        .card:hover { transform: translateY(-2px); }

        .notice { padding:1rem 1.25rem; border-radius: 12px; font-weight:600;
            font-size:.9rem; margin-bottom:1.5rem; display:none; }
        .notice-ok  { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .notice-err { background:#fff1f2; color:#e11d48; border: 1px solid #fecdd3; }

        .order-head { display:flex; justify-content:space-between; align-items:flex-start;
            flex-wrap:wrap; gap:1rem; padding-bottom:1.5rem; border-bottom:1px solid var(--border-color); margin-bottom:1.25rem; }
        .order-id { font-weight:800; font-size:1.15rem; color:var(--text-main); margin-bottom:.4rem; }
        .order-meta { font-size:.85rem; color:var(--text-muted); line-height:1.7; }
        .order-total { font-size:1.75rem; font-weight:900; color:var(--primary); text-align:right; letter-spacing: -0.025em; }
        
        .status-badge { padding:.3rem .8rem; border-radius:999px; font-size:.7rem; font-weight:700;
            margin-left:.6rem; vertical-align:middle; text-transform: uppercase; }
        .st-pending  { background:#fef3c7; color:#92400e; }
        .st-accepted { background:#dcfce7; color:#166534; }
        .st-rejected { background:#fee2e2; color:#b91c1c; }

        .act-row { display:flex; gap:.6rem; margin-top:.8rem; justify-content:flex-end; }
        
        .btn-accept, .btn-reject {
            border:none; cursor:pointer; padding:.65rem 1.4rem; border-radius: 10px;
            font-weight:700; font-size:.85rem; color:#fff; transition:all .25s ease;
        }
        .btn-accept { background:var(--primary); }
        .btn-accept:hover { background:var(--primary-hover); transform:translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,0.2); }
        .btn-reject { background:var(--danger); }
        .btn-reject:hover { background:var(--danger-hover); transform:translateY(-1px); box-shadow: 0 4px 12px rgba(239,68,68,0.2); }

        .items-cap { font-size:.75rem; text-transform:uppercase; letter-spacing:.06em;
            color:var(--text-muted); font-weight:800; margin-bottom:1rem; margin-top: 0.5rem; }
        .item-line { display:flex; align-items:center; gap:.9rem; font-size:.9rem; padding:.5rem 0; border-bottom: 1px dashed var(--border-color); }
        .item-line:last-child { border-bottom: none; }
        .dot { width:8px; height:8px; border-radius:50%; background:var(--primary); flex-shrink:0; opacity: 0.6; }
        .empty-card { text-align:center; color:var(--text-muted); padding:4rem; font-weight:500; font-size: 1.1rem; }

       
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

    <div class="top-row">
        <div>
            <h1>Purchase Requests</h1>
            <p class="page-sub">Review and manage all customer orders</p>
        </div>
        <span class="chip"><?= count($orders) ?> orders total</span>
    </div>

    <div class="tabs">
        <button class="tab active" id="tabAll"      onclick="filterOrders('all')">All</button>
        <button class="tab"        id="tabPending"  onclick="filterOrders('pending')">⏳ Pending</button>
        <button class="tab"        id="tabAccepted" onclick="filterOrders('accepted')">✓ Accepted</button>
        <button class="tab"        id="tabRejected" onclick="filterOrders('rejected')">✗ Rejected</button>
    </div>

    <div id="successMsg" class="notice notice-ok"></div>
    <div id="errorMsg"   class="notice notice-err"></div>

    <?php if (empty($orders)): ?>
        <div class="card empty-card">No orders placed yet.</div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
        <?php
            $custName  = $order['customer_name'] ?? $order['name'] ?? 'Customer';
            $custEmail = $order['customer_email'] ?? '';
            $items     = $order['items'] ?? [];
            $rawStatus = $order['status'] ?? 'pending';
            $status    = in_array($rawStatus, ['pending', 'accepted', 'rejected'])
                         ? $rawStatus : 'pending';
        ?>
        <div class="card order-card" data-status="<?= $status ?>">
            <div class="order-head">
                <div>
                    <div class="order-id">
                        Order #<?= intval($order['id']) ?>
                        <span class="status-badge st-<?= $status ?>" id="statusBadge_<?= intval($order['id']) ?>">
                            <?= ucfirst($status) ?>
                        </span>
                    </div>
                    <div class="order-meta">
                        👤 <b><?= htmlspecialchars($custName) ?></b>
                        <?= $custEmail ? ' &nbsp;•&nbsp; ' . htmlspecialchars($custEmail) : '' ?><br>
                        📍 <?= htmlspecialchars($order['shipping_address'] ?? '—') ?><br>
                        📅 <?= date('d M Y, h:i A', strtotime($order['order_date'])) ?>
                        &nbsp;•&nbsp; 💳 <?= htmlspecialchars($order['payment_method'] ?? '—') ?>
                    </div>
                </div>
                <div>
                    <div class="order-total">৳<?= number_format($order['total_amount'], 2) ?></div>
                    <?php if ($status === 'pending'): ?>
                    <div class="act-row" id="actionBtns_<?= intval($order['id']) ?>">
                        <button class="btn-accept" onclick="updateStatus(<?= intval($order['id']) ?>, 'accepted')">✓ Accept</button>
                        <button class="btn-reject" onclick="updateStatus(<?= intval($order['id']) ?>, 'rejected')">✗ Reject</button>
                    </div>
                    <?php else: ?>
                    <div id="actionBtns_<?= intval($order['id']) ?>"></div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($items)): ?>
            <div class="items-cap">Items Ordered</div>
            <div>
                <?php foreach ($items as $item): ?>
                <div class="item-line">
                    <span class="dot"></span>
                    <span style="flex:1;font-weight:600;color:var(--text-main)"><?= htmlspecialchars($item['medicine_name'] ?? '') ?></span>
                    <span style="color:var(--text-muted); margin: 0 1rem;">x<?= intval($item['quantity'] ?? 0) ?></span>
                    <span style="font-weight:700;color:var(--primary)">
                        ৳<?= number_format(($item['unit_price'] ?? 0) * ($item['quantity'] ?? 0), 2) ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <p style="color:var(--text-muted);font-size:.88rem;margin:0; font-style: italic;">Item breakdown not available for this order.</p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

</main>

<script>
function filterOrders(status) {
    document.querySelectorAll('.tab').forEach(function (b) { b.classList.remove('active'); });
    document.getElementById('tab' + status.charAt(0).toUpperCase() + status.slice(1)).classList.add('active');
    document.querySelectorAll('.order-card').forEach(function (card) {
        card.style.display = (status === 'all' || card.dataset.status === status) ? '' : 'none';
    });
}

function updateStatus(orderId, status) {
    var successEl = document.getElementById('successMsg');
    var errorEl   = document.getElementById('errorMsg');
    successEl.style.display = errorEl.style.display = 'none';


    fetch('index.php?page=ajax&type=order_status', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ order_id: orderId, status: status })
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (d.error) { errorEl.textContent = d.error; errorEl.style.display = 'block'; return; }

        var badge = document.getElementById('statusBadge_' + orderId);
        badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        badge.className   = 'status-badge st-' + status;

        var card = document.getElementById('actionBtns_' + orderId).closest('.order-card');
        card.dataset.status = status;
        document.getElementById('actionBtns_' + orderId).innerHTML = '';

        successEl.textContent = 'Order #' + orderId + ' has been ' + status + '.';
        successEl.style.display = 'block';
        setTimeout(function () { successEl.style.display = 'none'; }, 3000);
    })
    .catch(function () { errorEl.textContent = 'Request failed.'; errorEl.style.display = 'block'; });
}
</script>

</body>
</html>