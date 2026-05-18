<?php
require __DIR__ . '/../config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php?page=login");
    exit;
}

$user = $_SESSION['user']; 

$message = '';
$messageType = ''; 

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ? AND role = 'customer'");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Customer deleted successfully!";
        $messageType = "success";
    } else {
        $message = "Failed to delete customer!";
        $messageType = "error";
    }

    mysqli_stmt_close($stmt);
}


$result = mysqli_query($conn, "SELECT id, name, email, created_at FROM users WHERE role = 'customer' ORDER BY id DESC");
$customers = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Customers</title>
<link rel="stylesheet" href="admin.css">

<style>
:root {
    --primary: #3b82f6;
    --danger: #ef4444;
    --danger-hover: #dc2626;
    --success: #22c55e;
    --bg-main: #f8fafc;
    --text-dark: #0f172a;
    --text-muted: #64748b;
    --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
}

body {
    font-family: 'Inter', -apple-system, sans-serif;
    background-color: var(--bg-main);
    color: var(--text-dark);
    margin: 0;
    line-height: 1.5;
}

.main-content {
    max-width: 1400px;
    margin: 40px auto;
    padding: 0 24px;
}

h1 {
    font-size: 1.875rem;
    font-weight: 800;
    letter-spacing: -0.025em;
    margin-bottom: 2rem;
}

.message {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    font-weight: 600;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.success {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.error {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.navbar {
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    padding: 0.75rem 0;
    position: sticky;
    top: 0;
    z-index: 50;
}

.navbar-inner {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 24px;
}

.logo {
    font-weight: 800;
    font-size: 1.25rem;
    color: var(--primary);
}

.nav-links a {
    text-decoration: none;
    color: var(--text-muted);
    margin-right: 24px;
    font-weight: 500;
    transition: all 0.2s;
    font-size: 0.95rem;
}

.nav-links a:hover {
    color: var(--primary);
}

.nav-user {
    display: flex;
    align-items: center;
    gap: 16px;
}

.welcome {
    color: var(--text-muted);
    font-size: 0.9rem;
}

.logout-btn {
    background: #fee2e2;
    color: #dc2626;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
    transition: background 0.2s;
}

.logout-btn:hover {
    background: #fecaca;
}

.card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: var(--card-shadow);
    padding: 0;
    overflow: hidden;
    border: 1px solid #e2e8f0;
}

table {
    width: 100%;
    border-collapse: collapse;
    border: none !important;
}

th {
    background: #f8fafc;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    padding: 16px 24px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

td {
    padding: 18px 24px;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    font-size: 0.9375rem;
}

tr:last-child td {
    border-bottom: none;
}

tr:hover td {
    background-color: #fbfcfd;
}

.delete-link {
    display: inline-block;
    background-color: #fee2e2;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    border: 1px solid #fecaca;
    transition: all 0.2s ease;
    text-align: center;
    min-width: 80px;
}

.delete-link:hover {
    background-color: var(--danger);
    color: white !important;
    border-color: var(--danger-hover);
    box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.2);
    transform: translateY(-1px);
}

.delete-link:active {
    transform: translateY(0);
}

.empty-state {
    padding: 40px;
    text-align: center;
    color: var(--text-muted);
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

    <h1>Manage Customers</h1>

    <?php if ($message): ?>
        <div class="message <?= $messageType ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <div class="card">

        <?php if (empty($customers)): ?>
            <p class="empty-state">No customers found.</p>
        <?php else: ?>

        <table border="1" cellpadding="10" cellspacing="0">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($customers as $c): ?>
                <tr>
                    <td><?= intval($c['id']) ?></td>
                    <td><?= htmlspecialchars($c['name']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td><?= date('d M Y', strtotime($c['created_at'])) ?></td>
                    <td>
                        <a href="index.php?page=manageCustomer&delete=<?= intval($c['id']) ?>"
                           class="delete-link"
                           style="color:red;font-weight:bold;">
                            Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>

        <?php endif; ?>

    </div>

</main>

</body>
</html>