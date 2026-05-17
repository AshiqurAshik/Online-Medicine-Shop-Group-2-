<?php
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Dashboard</title>
<link rel="stylesheet" href="public/css/style.css">
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
            <a href="index.php?page=cart" style="margin-left: 15px; margin-right: 15px;">View Cart</a>
            <a href="index.php?page=logout">Logout</a>
        </div>

    </div>
</header>


<!-- ================= MAIN ================= -->

<main class="main-content">

    <h1>Customer Dashboard</h1>

    <!-- ================= SEARCH ================= -->

    <div class="card">

        <h3>Search Medicines</h3>

        <input type="text" placeholder="Search medicine..." id="searchBox">

    </div>


    <!-- ================= MEDICINE LIST ================= -->

    <div class="card">

        <h3>Available Medicines</h3>

        <table border="1" width="100%">

            <tr>
                <th>Name</th>
                <th>Vendor</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>

            <?php foreach ($medicines ?? [] as $m): ?>

                <tr>

                    <td><?= htmlspecialchars($m['name']) ?></td>
                    <td><?= htmlspecialchars($m['vendor_name']) ?></td>
                    <td><?= $m['price'] ?></td>
                    <td><?= $m['availability'] ?></td>

                    <td>
                        <form method="POST" action="index.php?page=customer&action=addToCart">
                            <input type="hidden" name="medicine_id" value="<?= $m['id'] ?>">
                            <button type="submit">Add to Cart</button>
                        </form>
                    </td>

                </tr>

            <?php endforeach; ?>

        </table>

    </div>


    <!-- ================= ORDERS ================= -->

    <div class="card">

        <h3>My Orders</h3>

        <table border="1" width="100%">

            <tr>
                <th>Order ID</th>
                <th>Total</th>
                <th>Status</th>
            </tr>

            <?php foreach ($orders ?? [] as $o): ?>

                <tr>
                    <td><?= $o['id'] ?></td>
                    <td><?= $o['total_amount'] ?></td>
                    <td><?= htmlspecialchars($o['status']) ?></td>
                </tr>

            <?php endforeach; ?>

        </table>

    </div>

</main>

</body>
</html>