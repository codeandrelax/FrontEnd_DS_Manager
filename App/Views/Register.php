<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Name - Register</title>
    <link rel="stylesheet" href="/public/resources/css/index.css">
    <link rel="icon" href="/public/resources/images/favicon.svg" type="image/svg+xml">
</head>

<body>
    <div class="main-container">
        <section class="register-section">
            <div class="brand">
                <div class="logo">Server Name</div>
            </div>

            <div class="register-header">
                <h1>Register Account</h1>
                <p>Enter your details to register</p>
            </div>

            <form method="POST" action="/register">
                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                <!--   <p>CSRF Token (form): <?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>    </p> -->

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Enter your username" required autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email address" required autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required autocomplete="new-password">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required autocomplete="new-password">
                </div>

                <button type="submit" class="register-button">Register</button>

                <!-- Error or success message below the button -->
                <p class="error-message">
                    <?php if (isset($_SESSION['register_error'])): ?>
                        <?= htmlspecialchars($_SESSION['register_error']); ?>
                        <?php unset($_SESSION['register_error']); ?>
                    <?php elseif (isset($_SESSION['register_success'])): ?>
                        <span class="success-message"><?= htmlspecialchars($_SESSION['register_success']); ?></span>
                        <?php unset($_SESSION['register_success']); ?>
                    <?php endif; ?>
                </p>
            </form>
        </section>

        <p><a href="/login">Login</a></p>

        <section class="image-section">
            <div class="image-overlay"></div>
        </section>
    </div>
</body>

</html>