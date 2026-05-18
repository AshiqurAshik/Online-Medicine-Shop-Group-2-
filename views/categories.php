<?php
$user    = $_SESSION['user'] ?? ['name' => 'Admin'];
$editing = $editing ?? null;
$error   = $error   ?? '';
$action  = $_GET['action'] ?? '';
$isAdd   = $action === 'add';
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Categories — Online Medicine Shop</title>
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
            --radius-md: 12px;
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

        .top-row { 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start; 
            flex-wrap: wrap; 
            gap: 1rem; 
            margin-bottom: 2rem; 
        }

        h1 { font-size: 1.875rem; font-weight: 800; color: var(--text-main); }
        .page-sub { color: var(--text-muted); font-size: .95rem; margin-top: .35rem; }

        .btn-add {
            text-decoration: none; 
            background: var(--primary); 
            color: #fff;
            padding: 0.75rem 1.5rem; 
            border-radius: var(--radius-md); 
            font-weight: 700;
            font-size: .9rem; 
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            transition: all .3s ease; 
            white-space: nowrap;
        }

        .btn-add:hover { background: var(--primary-hover); transform: translateY(-2px); }

        .card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            margin-bottom: 2rem;
        }

        .notice { padding: 1rem 1.25rem; border-radius: var(--radius-md); font-weight: 600; font-size: .9rem; margin-bottom: 1.5rem; }
        .notice-ok { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .notice-err { background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; }

        .cat-form .field { margin-bottom: 1.25rem; }
        .cat-form label { 
            display: block; 
            font-size: .75rem; 
            font-weight: 700; 
            text-transform: uppercase;
            letter-spacing: .05em; 
            color: var(--text-muted); 
            margin-bottom: .5rem; 
        }

        .cat-form input, .cat-form select {
            width: 100%; 
            background: #fcfdfe; 
            border: 1px solid var(--border-color); 
            padding: 0.75rem 1rem;
            border-radius: 8px; 
            font-size: .95rem; 
            color: var(--text-main);
            transition: all .2s ease;
        }

        .cat-form input:focus, .cat-form select:focus {
            outline: none; 
            background: #fff; 
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .form-actions { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem; }
        
        .btn-ghost, .btn-solid {
            padding: .75rem 1.5rem; 
            border-radius: 8px; 
            font-weight: 700;
            font-size: .9rem; 
            border: none; 
            cursor: pointer; 
            text-decoration: none;
            display: inline-flex; 
            align-items: center; 
            transition: all .3s ease;
        }

        .btn-ghost { background: #f1f5f9; color: var(--text-main); }
        .btn-ghost:hover { background: #e2e8f0; }
        .btn-solid { background: var(--primary); color: #fff; }
        .btn-solid:hover { background: var(--primary-hover); transform: translateY(-1px); }

        .req { color: #e11d48; }

        .tag { display: inline-block; padding: .3rem .7rem; border-radius: 999px; font-size: .75rem; font-weight: 700; }
        .tag-solid { background: #eff6ff; color: var(--primary); }
        .tag-liquid { background: #ecfeff; color: #0e7490; }
        .count-chip { background: #eff6ff; color: var(--primary); font-weight: 700; font-size: .75rem; padding: .3rem .8rem; border-radius: 999px; margin-left: .6rem; }

        table { width: 100%; border-collapse: collapse; text-align: left; }
        table th { background: #f1f5f9; color: var(--text-muted); padding: 1rem; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; }
        table td { padding: 1rem; border-bottom: 1px solid var(--border-color); font-size: 0.875rem; }
        table tr:last-child td { border-bottom: none; }
        table tr:hover td { background-color: #f8fafc; }

        .empty-row td { text-align: center; color: var(--text-muted); padding: 2.5rem; font-weight: 500; }
        .row-num { color: var(--text-muted); font-size: .85rem; }
        .err-text { color: #e11d48; font-size: .8rem; display: block; margin-top: .4rem; }

        
</style>
</head>

<body>

<header class="navbar">

    <div class="navbar-inner">

        <!-- LOGO -->
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

    <div class="top-row">
        <div>
            <h1>Category Management</h1>
            <p class="page-sub">Manage medicine categories and their types</p>
        </div>
        <?php if (!$editing && !$isAdd): ?>
            <a href="index.php?page=categories&action=add" class="btn-add">+ Add Category</a>
        <?php endif; ?>
    </div>

    
    <?php if (!empty($error)): ?>
        <div class="notice notice-err"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['msg'])): ?>
        <?php
        $msgMap = [
            'added'   => 'Category added.',
            'updated' => 'Category updated.',
            'deleted' => 'Category deleted.',
        ];
        $msgText = $msgMap[$_GET['msg']] ?? '';
        ?>
        <?php if ($msgText): ?>
            <div class="notice notice-ok"><?= htmlspecialchars($msgText) ?></div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($isAdd || $editing): ?>
    <div class="card" style="max-width:520px; margin-left: auto; margin-right: auto;">
        <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;"><?= $editing ? 'Edit Category' : 'New Category' ?></h2>

        <form method="POST" id="catForm" class="cat-form" novalidate
              action="index.php?page=categories&action=<?= $editing ? 'update' : 'add' ?>">

            <?php if ($editing): ?>
                <input type="hidden" name="id" value="<?= intval($editing['id']) ?>">
            <?php endif; ?>

            <div class="field">
                <label for="catName">Category Name <span class="req">*</span></label>
                <input type="text" id="catName" name="name"
                       value="<?= htmlspecialchars($editing['name'] ?? '') ?>"
                       placeholder="e.g. Paracetamol" required autofocus>
                <span id="catNameErr" class="err-text"></span>
            </div>

            <div class="field">
                <label for="catType">Category Type <span class="req">*</span></label>
                <select id="catType" name="type">
                    <option value="solid"  <?= (($editing['category_type'] ?? 'solid') === 'solid')  ? 'selected' : '' ?>>💊 Solid</option>
                    <option value="liquid" <?= (($editing['category_type'] ?? '') === 'liquid') ? 'selected' : '' ?>>💧 Liquid</option>
                </select>
            </div>

            <div class="form-actions">
                <a href="index.php?page=categories" class="btn-ghost">Cancel</a>
                <button type="submit" class="btn-solid"><?= $editing ? 'Update' : 'Add Category' ?></button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <div class="card">
        <h2 style="display:flex;align-items:center;border-left:none;padding-left:0; margin-bottom: 1.5rem; font-size: 1.25rem;">
            All Categories
            <span class="count-chip"><?= count($categories ?? []) ?> total</span>
        </h2>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($categories)): ?>
                <tr class="empty-row"><td colspan="5">No categories yet. Add one above.</td></tr>
            <?php else: ?>
                <?php foreach ($categories as $i => $cat): ?>
                <tr>
                    <td class="row-num"><?= $i + 1 ?></td>
                    <td style="font-weight:700;color:var(--text-main)"><?= htmlspecialchars($cat['name']) ?></td>
                    <td>
                        <?php $ct = $cat['category_type'] ?? 'solid'; ?>
                        <span class="tag <?= $ct === 'liquid' ? 'tag-liquid' : 'tag-solid' ?>">
                            <?= $ct === 'liquid' ? '💧' : '💊' ?> <?= ucfirst(htmlspecialchars($ct)) ?>
                        </span>
                    </td>
                    <td style="color:var(--text-muted);font-size:.9rem">
                        <?= !empty($cat['created_at']) ? date('d M Y', strtotime($cat['created_at'])) : '—' ?>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            <a href="index.php?page=categories&action=edit&id=<?= intval($cat['id']) ?>" 
                               style="text-decoration: none; color: var(--primary); font-weight: 600; font-size: 0.8rem;">✎ Edit</a>
                            <span style="color: var(--border-color)">|</span>
                            <a href="index.php?page=categories&action=delete&id=<?= intval($cat['id']) ?>"
                               style="text-decoration: none; color: var(--danger); font-weight: 600; font-size: 0.8rem;"
                               onclick="return confirm('Delete category \'<?= htmlspecialchars(addslashes($cat['name'])) ?>\'? This cannot be undone.')">
                               🗑 Delete
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>

<script>
var f = document.getElementById('catForm');
if (f) {
    f.addEventListener('submit', function (e) {
        var name = document.getElementById('catName').value.trim();
        document.getElementById('catNameErr').textContent = '';
        if (!name) {
            document.getElementById('catNameErr').textContent = 'Category name is required.';
            e.preventDefault();
        }
    });
}
</script>

</body>
</html>