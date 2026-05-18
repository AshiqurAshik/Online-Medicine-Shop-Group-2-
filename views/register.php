<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MediShield Global</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background: #e5e7eb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .register-container {
            display: flex;
            width: 100%;
            max-width: 1100px;
            min-height: 780px;
            background: #fff;
            overflow: hidden;
            border-radius: 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        .left-side {
            flex: 1;
            position: relative;
            background: linear-gradient(rgba(37, 99, 235, 0.8), rgba(30, 64, 175, 0.9)),
                        url('https://images.unsplash.com/photo-1587854692152-cbe660dbde88?q=80&w=1200&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 40px;
        }

        .left-content { width: 85%; z-index: 2; }
        .logo { font-size: 50px; margin-bottom: 15px; }
        .left-content h1 { font-size: 38px; font-weight: 800; margin-bottom: 15px; line-height: 1.2; }
        .left-content p { font-size: 16px; opacity: 0.9; margin-bottom: 25px; line-height: 1.6; }

        .features { display: flex; flex-direction: column; gap: 12px; }
        .feature-card {
            background: rgba(255, 255, 255, 0.15);
            padding: 12px 18px;
            border-radius: 12px;
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .right-side {
            flex: 1.2;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px;
            background: #fff;
        }

        .form-box { width: 100%; max-width: 480px; }
        .form-box h2 { font-size: 30px; color: #111827; margin-bottom: 4px; }
        .subtitle { color: #6b7280; margin-bottom: 24px; font-size: 14px; }

        .alert {
            display: none;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
        }

        .error   { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }

        .register-form { display: flex; flex-direction: column; gap: 16px; }
        .input-group { display: flex; flex-direction: column; gap: 6px; }
        .input-group label { font-size: 13px; font-weight: 600; color: #374151; }

        .input-group input, .input-group select, .input-group textarea {
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.2s;
            outline: none;
            background: #f9fafb;
        }

        .input-group input:focus, .input-group select:focus, .input-group textarea:focus {
            border-color: #2563eb;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .input-group.invalid input, .input-group.invalid select {
            border-color: #ef4444;
            background-color: #fef2f2;
        }

        .double-input { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        textarea { resize: none; height: 70px; }

        .register-btn {
            background: #2563eb;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }

        .register-btn:hover { background: #1d4ed8; transform: translateY(-1px); }

        .bottom-text { text-align: center; margin-top: 24px; font-size: 14px; color: #6b7280; }
        .bottom-text a { color: #2563eb; text-decoration: none; font-weight: 700; }

    </style>
</head>
<body>

<div class="register-container">
    <div class="left-side">
        <div class="left-content">
            <div class="logo">🏥</div>
            <h1>MediShield Global</h1>
            <p>Elevating healthcare standards through technology. Access a curated selection of laboratory-verified medicines with clinical supervision.</p>
            <div class="features">
                <div class="feature-card">
                    <span>🛡️</span>
                    <div>
                        <strong style="display: block; font-size: 14px;">Certified Authenticity</strong>
                        <p style="font-size: 12px; margin: 0; opacity: 0.8;">100% FDA-approved and lab-tested.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <span>⚡</span>
                    <div>
                        <strong style="display: block; font-size: 14px;">Cold-Chain Logistics</strong>
                        <p style="font-size: 12px; margin: 0; opacity: 0.8;">Ensuring temperature-sensitive delivery.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <span>🎓</span>
                    <div>
                        <strong style="display: block; font-size: 14px;">Licensed Consultation</strong>
                        <p style="font-size: 12px; margin: 0; opacity: 0.8;">Direct access to clinical pharmacists.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="right-side">
        <div class="form-box">
            <h2>Create Account</h2>
            <p class="subtitle">Join our community today.</p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert error" style="display:block;">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert success" style="display:block;">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <div id="validation-errors" class="alert error"></div>

            <form id="registrationForm" action="index.php?page=register" method="POST" class="register-form" novalidate>

                <div class="input-group" id="group-name">
                    <label>Full Name</label>
                    <input type="text" name="name" id="name" placeholder="Enter Full Name" required>
                </div>

                <div class="input-group" id="group-email">
                    <label>Email Address</label>
                    <input type="email" name="email" id="email" placeholder="Enter Email Address" required>
                </div>

                <div class="double-input">
                    <div class="input-group" id="group-phone">
                        <label>Phone</label>
                        <input type="text" name="phone" id="phone" placeholder="Enter Phone Number" required>
                    </div>
            
                    <div class="input-group" id="group-role">

        <label>Role</label>

        <select
            name="role"
            id="role"
            required
        >
            <option value="">Select Role</option>
            <option value="customer">Customer</option>
            <option value="admin">Admin</option>
        </select>



</div>
                </div>

                <div class="input-group" id="group-address">
                    <label>Shipping Address</label>
                    <textarea name="address" id="address" placeholder="Enter delivery details" required></textarea>
                </div>

                <div class="double-input">
                    <div class="input-group" id="group-password">
                        <label>Password</label>
                        <input type="password" name="password" id="password" placeholder="Enter Password" required>
                    </div>
                    <div class="input-group" id="group-confirm">
                        <label>Confirm</label>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                    </div>
                </div>

                <button type="submit" class="register-btn">Sign Up</button>
            </form>

            <div class="bottom-text">
                Already registered? <a href="index.php?page=login">Login</a>
            </div>
        </div>
    </div>
</div>

<script>
    var form     = document.getElementById('registrationForm');
    var errorBox = document.getElementById('validation-errors');

    form.addEventListener('submit', function (e) {
        var errors = [];

        document.querySelectorAll('.input-group').forEach(function (g) { g.classList.remove('invalid'); });
        errorBox.style.display = 'none';
        errorBox.innerHTML = '';

        var name = document.getElementById('name').value.trim();
        if (name.length < 2) {
            errors.push('Please enter a valid full name (at least 2 characters).');
            document.getElementById('group-name').classList.add('invalid');
        }

        var email      = document.getElementById('email').value.trim();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errors.push('Please enter a valid email address.');
            document.getElementById('group-email').classList.add('invalid');
        }

        var phone      = document.getElementById('phone').value.trim();
        var phoneRegex = /^[0-9+\-\s]{10,15}$/;
        if (!phoneRegex.test(phone)) {
            errors.push('Please enter a valid phone number (10-15 digits).');
            document.getElementById('group-phone').classList.add('invalid');
        }

        var address = document.getElementById('address').value.trim();
        if (address.length < 10) {
            errors.push('Please provide a more detailed shipping address (at least 10 characters).');
            document.getElementById('group-address').classList.add('invalid');
        }

        var password        = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirm_password').value;

        if (password.length < 8) {
            errors.push('Password must be at least 8 characters long.');
            document.getElementById('group-password').classList.add('invalid');
        } else if (password !== confirmPassword) {
            errors.push('Passwords do not match.');
            document.getElementById('group-confirm').classList.add('invalid');
        }

        if (errors.length > 0) {
            e.preventDefault();
            errorBox.innerHTML = '<ul style="padding-left:20px">' +
                errors.map(function (err) { return '<li>' + err + '</li>'; }).join('') +
                '</ul>';
            errorBox.style.display = 'block';
            errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>

</body>
</html>