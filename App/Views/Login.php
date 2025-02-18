<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Development</title>
    <link rel="stylesheet" href="/public/resources/css/index.css">
    <link rel="icon" href="/public/resources/images/favicon.svg" type="image/svg+xml">
</head>

<body>
    <div class="main-container">

        <section class="login-section">
            <div class="brand">
                <div class="logo">Naziv Necega</div>
            </div>

            <div class="login-header">
                <h1>Welcome</h1>
                <p>Please log in to continue</p>
            </div>

            <form method="POST" action="/login">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                <!--   <p>CSRF Token (form): <?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>    </p> -->

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                </div>

                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Remember me</span>
                    </label>
                </div>

                <button type="submit" class="login-button">Log in to your account</button>
                <p class="error-message">
                    <?php if (isset($_SESSION['login_error'])): ?>
                        <?= htmlspecialchars($_SESSION['login_error']); ?>
                        <?php unset($_SESSION['login_error']); ?>
                    <?php endif; ?>
                </p>
            </form>
        </section>
        <p><a href="/register">Register</a></p>
        <section class="image-section">
            <div class="image-overlay"></div>
        </section>
    </div>

</body>

</html>