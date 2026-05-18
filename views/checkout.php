<?php
 $user = $_SESSION['user']; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<header class="navbar">
    <div class="navbar-inner">
        <h2>Online Medicine Shop</h2>
        
        <div>
            <span>Welcome, <?= htmlspecialchars($user['name']) ?></span>
            
            <a href="index.php?page=cart" style="margin-left: 15px; margin-right: 15px;">Back to Cart</a>
             <a href="index.php?page=logout">Logout</a>
        </div>
    </div>
</header>

<main class="main-content">
    <h1>Checkout</h1>

    <div class="card">
        <h2>Order Summary</h2>
        
        <table border="1" width="100%" style="border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
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
                        <td style="padding: 10px; text-align: center;"><?= $item['quantity'] ?></td>
                        
                        <td style="padding: 10px;">$<?= number_format($subtotal, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div style="text-align: right; font-size: 18px; font-weight: bold; margin-bottom: 20px;">
            Total to Pay: $<?= number_format($total, 2) ?>
        </div>
    </div>

    <div class="card">
        <h2>Shipping & Payment Details</h2>

        <?php if (!empty($error)): ?>
            <div style="color: red; background: #fee; border: 1px solid red; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=checkout">
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Shipping Address</label>
                <textarea name="address" required placeholder="Enter delivery address..." style="width: 100%; height: 80px; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"></textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Payment Method</label>
                
                <div style="margin-bottom: 8px;">
                    <label style="font-weight: normal; cursor: pointer;">
                        <input type="radio" name="payment_method" value="Cash on Delivery" checked>
                        Cash on Delivery
                    </label>
                </div>
                
                <div style="margin-bottom: 8px;">
                    <label style="font-weight: normal; cursor: pointer;">
                        <input type="radio" name="payment_method" value="Bkash / Nagad">
                        bKash / Nagad
                    </label>
                </div>
                
                <div>
                    <label style="font-weight: normal; cursor: pointer;">
                        <input type="radio" name="payment_method" value="Card Payment">
                        Credit / Debit Card
                    </label>
                </div>
                
            </div>

            <button type="submit" style="background: #333; color: white; border: none; padding: 10px 20px; font-size: 16px; font-weight: bold; border-radius: 4px; cursor: pointer; width: 100%;">Confirm Order</button>
        </form>
    </div>
</main>

</body>
</html>
