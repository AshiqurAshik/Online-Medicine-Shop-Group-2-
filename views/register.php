<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Online Medicine Shop</title>
    <link rel="stylesheet" href="register.css">
</head>
<body class="auth-body">

<div class="auth-shell">

    <!-- ================= LEFT SIDE ================= -->
    <div class="auth-side">
        <div class="logo-big">
            &#128138;
        </div>
        <h1>Create Your Account</h1>
        <p>Register now to purchase medicines online and manage your orders easily.</p>

        <ul class="feature-list">
            <li>Buy medicines online</li>
            <li>Secure user account</li>
            <li>Fast medicine ordering</li>
            <li>Track orders easily</li>
        </ul>
    </div>

    <!-- ================= REGISTER FORM ================= -->
    <div class="auth-form-wrap">
        <div class="auth-card">
            <h2>Create Account</h2>
            <p class="muted">Register as a customer</p>

            <!-- Success/Error simulation (normally handled by PHP) -->
            <!-- <div class="alert alert-error">Example Error Message</div> -->

            <form method="POST" action="#" class="form">
                <div class="field">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter full name" required>
                </div>

                <div class="field">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter email" required>
                </div>

                <div class="field">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" placeholder="Enter phone number" required>
                </div>

                <div class="field">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" placeholder="Enter full delivery address" required></textarea>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Min. 8 chars" required>
                    </div>
                    <div class="field">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password" required>
                    </div>
                </div>

                <input type="hidden" name="role" value="customer">

                <button type="submit" class="btn btn-primary btn-block">
                    Create Account
                </button>
            </form>

            <p class="auth-foot">
                Already have an account?
                <a href="index.php?page=login">Sign In</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>