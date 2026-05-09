<?php
$error = flash_get('error');
$success = flash_get('success');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lab Sentry - Login</title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars(url_for('assets/css/style.css'), ENT_QUOTES); ?>" />
</head>

<body class="auth-body">
    <main class="authPage">
        <section class="authCard authCard--cyan">
            <div class="authCard__accent">
                <div class="authCard__accentInner">
                    <h1 class="authCard__accentTitle">Welcome Back!</h1>
                    <p class="authCard__accentText">Don't have an account?</p>
                    <a class="btn btn--ghost" href="<?php echo htmlspecialchars(url_for('index.php', ['page' => 'register']), ENT_QUOTES); ?>">Register</a>
                </div>
            </div>

            <div class="authCard__form">
                <h2 class="authCard__title">Login</h2>

                <?php if ($error): ?>
                    <div class="alert alert--error"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert--success"><?php echo htmlspecialchars($success, ENT_QUOTES); ?></div>
                <?php endif; ?>

                <form class="form" method="post" action="<?php echo htmlspecialchars(url_for('modules/auth/login.php'), ENT_QUOTES); ?>" autocomplete="off">
                    <label class="field">
                        <div class="field__control field__control--icon">
                            <span class="field__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="currentColor" stroke-width="1.8" />
                                    <path d="M20 21a8 8 0 1 0-16 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                </svg>
                            </span>
                            <input class="input" type="text" name="username" placeholder="Username" required />
                        </div>
                    </label>

                    <label class="field">
                        <div class="field__control field__control--icon">
                            <span class="field__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 11V8a5 5 0 0 1 10 0v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                    <path d="M6.5 11h11A1.5 1.5 0 0 1 19 12.5v7A1.5 1.5 0 0 1 17.5 21h-11A1.5 1.5 0 0 1 5 19.5v-7A1.5 1.5 0 0 1 6.5 11Z" stroke="currentColor" stroke-width="1.8" />
                                </svg>
                            </span>
                            <input class="input" type="password" name="password" placeholder="Password" required />
                        </div>
                    </label>

                    <div class="authCard__forgot">Forgot Password?</div>
                    <button class="btn btn--cyan" type="submit">Login</button>
                </form>
            </div>
        </section>
    </main>
</body>

</html>