<?php

$user = $user ?? $_SESSION['user'];

$dashPage = ($user['role'] ?? 'customer') === 'admin' ? 'admin' : 'customer';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - MediShield Global</title>
    <style>
        :root {
            --primary: #0d9488;
            --primary-dark: #0f766e;
            --secondary: #0369a1;
            --bg-canvas: #f3f4f6;
            --text-main: #111827;
            --text-muted: #6b7280;
            --white: #ffffff;
            --error: #be123c;
            --success: #15803d;
            --radius: 16px;
            --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background-color: var(--bg-canvas);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .profile-container {
            width: 100%;
            max-width: 650px;
        }

        .profile-card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
            padding: 8px 12px;
            border-radius: 8px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            z-index: 10;
        }

        .back-btn:hover {
            color: var(--primary);
            background: #f0fdfa;
            border-color: var(--primary);
            transform: translateX(-3px);
        }

        .profile-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
        }

        h2 {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 30px;
            text-align: center;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }

        .avatar {
            width: 130px;
            height: 130px;
            margin: 0 auto 30px;
            position: relative;
            border-radius: 50%;
            padding: 5px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            box-shadow: 0 4px 15px rgba(13, 148, 136, 0.2);
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid var(--white);
            background: #eee;
        }

        form { display: flex; flex-direction: column; }

        label {
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-main);
            display: block;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.95rem;
            margin-bottom: 20px;
            transition: all 0.2s;
            outline: none;
            background: #f9fafb;
        }

        input:focus, textarea:focus {
            background: var(--white);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.1);
        }

        textarea { resize: none; height: 80px; }

        input[type="file"] { padding: 8px; background: #fff; font-size: 0.85rem; }

        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 0.9rem;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .alert.error   { background: #fff1f2; color: var(--error);   border-color: #fecdd3; }
        .alert.success { background: #f0fdf4; color: var(--success); border-color: #bbf7d0; }

        hr { border: 0; border-top: 1px solid #f3f4f6; margin: 10px 0 30px; }

        h3 { font-size: 1.1rem; margin-bottom: 20px; color: var(--secondary); font-weight: 700; }

        button {
            background: var(--primary);
            color: var(--white);
            padding: 16px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 10px;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.2);
        }

        button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(13, 148, 136, 0.3);
        }

        
    </style>
</head>

<body>

<div class="profile-container">

    <div class="profile-card">

        <a href="index.php?page=<?= $dashPage ?>" class="back-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            Back to Dashboard
        </a>

        <h2>My Profile</h2>

        <?php if (!empty($error)): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="avatar">
            <?php
            $pic = basename($user['profile_picture'] ?? '');
            $src = $pic ? 'uploads/' . htmlspecialchars($pic) : '';
            ?>
            <img src="<?= $src ?: 'https://ui-avatars.com/api/?name=' . urlencode($user['name']) . '&background=0d9488&color=fff' ?>"
                 alt="Profile Picture"
                 onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=0d9488&color=fff'">
        </div>

        <form method="POST" enctype="multipart/form-data" action="index.php?page=profile">

            <div>
                <label>Full Name</label>
                <input type="text" name="name"
                       value="<?= htmlspecialchars($user['name'] ?? '') ?>"
                       placeholder="John Doe" required>
            </div>

            <div>
                <label>Email Address</label>
                <input type="email" name="email"
                       value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                       placeholder="name@example.com" required>
            </div>

            <div>
                <label>Phone Number</label>
                <input type="text" name="phone"
                       value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                       placeholder="01XXX XXXXXX">
            </div>

            <div>
                <label>Shipping Address</label>
                <textarea name="address"
                          placeholder="Enter your full address"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
            </div>

            <div>
                <label>Change Profile Picture</label>
                <input type="file" name="profile_picture" accept="image/*">
            </div>

            <hr>

            <h3>Security &amp; Password</h3>

            <div>
                <label>Current Password</label>
                <input type="password" name="current_password"
                       placeholder="Enter current password to verify">
            </div>

            <div>
                <label>New Password <small style="font-weight:400;color:var(--text-muted)">(leave blank to keep current)</small></label>
                <input type="password" name="new_password"
                       placeholder="Leave blank to keep current">
            </div>

            <button type="submit">Update Profile Information</button>

        </form>

    </div>

</div>

</body>
</html>