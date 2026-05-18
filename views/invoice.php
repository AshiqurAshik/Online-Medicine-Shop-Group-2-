<?php
 $user = $_SESSION['user']; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $order['id'] ?></title>
    
    <link rel="stylesheet" href="admin.css">
    <style>
        @media print {
            .navbar, .btn-print, .btn-shop {
                display: none !important;
            }
            .main-content {
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body>

<header class="navbar">
    <div class="navbar-inner">
        <h2>Online Medicine Shop</h2>
        <div>
            
            <span>Welcome, <?= htmlspecialchars($user['name']) ?></span>
            
            <a href="index.php?page=customer" class="btn-shop" style="margin-left: 15px; margin-right: 15px;">Continue Shopping</a>
             <a href="index.php?page=logout">Logout</a>
        </div>
    </div>
</header>

<main class="main-content">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #28a745; margin-bottom: 5px;">Order Confirmed!</h1>
        <p style="font-size: 16px; margin-top: 0;">Thank you for your order. Your invoice is generated below.</p>
    </div>

    <div class="card" style="padding: 30px; border: 2px solid #eee;">
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
            <div>
                <h2 style="margin: 0 0 5px 0;">MediCare Pharmacy</h2>
                <p style="margin: 0; color: #555;">Dhaka, Bangladesh</p>
                <p style="margin: 0; color: #555;">Phone: 01700000000</p>
            </div>
            <div style="text-align: right;">
                <h2 style="margin: 0 0 5px 0; color: #555;">INVOICE</h2>
                <p style="margin: 0;"><strong>Invoice ID:</strong> #<?= $order['id'] ?></p>
                
                <p style="margin: 0;"><strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($order['order_date'])) ?></p>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 20px;">

        <div style="display: flex; justify-content: space-between; margin-bottom: 30px;">
            <div>
                <h4 style="margin: 0 0 5px 0; color: #777;">Billed To:</h4>
                <p style="margin: 0; font-weight: bold;"><?= htmlspecialchars($order['name']) ?></p>
                <p style="margin: 0; color: #555;"><?= htmlspecialchars($order['email']) ?></p>
            </div>
            
            <div style="text-align: right;">
                <h4 style="margin: 0 0 5px 0; color: #777;">Shipping Info:</h4>
                <p style="margin: 0; white-space: pre-line;"><?= htmlspecialchars($order['shipping_address']) ?></p>
                
                <p style="margin: 5px 0 0 0;"><strong>Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
                <p style="margin: 0;"><strong>Status:</strong> <span style="text-transform: capitalize; font-weight: bold; color: #d39e00;"><?= htmlspecialchars($order['status']) ?></span></p>
            </div>
        </div>

        <table border="1" width="100%" style="border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th style="padding: 10px; text-align: left;">Medicine</th>
                    <th style="padding: 10px; text-align: right;">Price</th>
                    <th style="padding: 10px; text-align: center;">Quantity</th>
                    <th style="padding: 10px; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td style="padding: 10px; text-align: left;"><?= htmlspecialchars($item['name']) ?></td>
                        
                        <td style="padding: 10px; text-align: right;">$<?= number_format($item['unit_price'], 2) ?></td>
                        <td style="padding: 10px; text-align: center;"><?= $item['quantity'] ?></td>
                        
                        <td style="padding: 10px; text-align: right;">$<?= number_format($item['unit_price'] * $item['quantity'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                
                <tr style="font-weight: bold; background: #f8f9fa;">
                    <td colspan="3" style="padding: 10px; text-align: right;">Grand Total:</td>
                    <td style="padding: 10px; text-align: right;">$<?= number_format($order['total_amount'], 2) ?></td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: center; margin-top: 30px; font-style: italic; color: #777;">
            This is a computer-generated invoice. No signature is required.
        </div>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" class="btn-print" style="background: #333; color: white; border: none; padding: 10px 20px; font-weight: bold; border-radius: 4px; cursor: pointer; font-size: 16px;">Print Invoice</button>
    </div>
</main>

</body>
</html>
