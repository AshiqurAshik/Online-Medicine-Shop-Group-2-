<?php
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<header class="navbar">
    <div class="navbar-inner">
        <h2>Online Medicine Shop</h2>
        <div>
            
            <span>Welcome, <?= htmlspecialchars($user['name']) ?></span>
            
            <a href="index.php?page=customer" style="margin-left: 15px; margin-right: 15px;">Shop</a>
             <a href="index.php?page=logout">Logout</a>
        </div>
    </div>
</header>

<main class="main-content">
    
    <h1>Your Shopping Cart</h1>

    <div class="card">
        <?php if (empty($cartItems)): ?>
            
            <p>Your cart is empty.</p>
            <a href="index.php?page=customer" style="text-decoration: none; display: inline-block; background: #333; color: white; padding: 8px 15px; border-radius: 4px;">Go to Shop</a>
            
        <?php else: ?>
            <table border="1" width="100%" style="border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                     $total = 0;
                     
                    foreach ($cartItems as $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td style="padding: 10px;"><?= htmlspecialchars($item['name']) ?></td>
                            
                            <td style="padding: 10px;">$<?= number_format($item['price'], 2) ?></td>
                            <td style="padding: 10px; text-align: center;">
                                
                                <a href="index.php?page=cart&action=update&id=<?= $item['id'] ?>&qty=<?= $item['quantity'] - 1 ?>" style="text-decoration: none; font-weight: bold; font-size: 16px; padding: 2px 8px; border: 1px solid #ccc; background: #eee; color: #333;">-</a>
                                <span style="margin: 0 10px; font-weight: bold;"><?= $item['quantity'] ?></span>
                                
                                <?php if ($item['quantity'] < $item['availability']): ?>
                                    <a href="index.php?page=cart&action=update&id=<?= $item['id'] ?>&qty=<?= $item['quantity'] + 1 ?>" style="text-decoration: none; font-weight: bold; font-size: 16px; padding: 2px 8px; border: 1px solid #ccc; background: #eee; color: #333;">+</a>
                                <?php else: ?>
                                     <span style="font-weight: bold; font-size: 16px; padding: 2px 8px; border: 1px solid #ccc; background: #ddd; color: #aaa; cursor: not-allowed;">+</span>
                                <?php endif; ?>
                                
                            </td>
                            <td style="padding: 10px;">$<?= number_format($subtotal, 2) ?></td>
                            <td style="padding: 10px; text-align: center;">
                                <a href="index.php?page=cart&action=remove&id=<?= $item['id'] ?>" onclick="return confirm('Remove this item?')" style="color: red; text-decoration: none;">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="text-align: right; font-size: 18px; font-weight: bold; margin-bottom: 20px;">
                Total Amount: $<?= number_format($total, 2) ?>
            </div>

            <div style="text-align: right;">
                <a href="index.php?page=checkout" style="text-decoration: none; background: #333; color: white; padding: 10px 20px; font-weight: bold; border-radius: 4px; display: inline-block;">Proceed to Checkout</a>
            </div>
            
        <?php endif; ?>
    </div>
</main>

</body>
</html>
