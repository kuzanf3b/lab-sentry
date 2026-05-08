<?php
$error = flash_get('error');
$success = flash_get('success');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lab Sentry - Register</title>
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body class="auth-body">
    <main class="auth auth--reverse">
        <section class="auth__panel auth__panel--form">
            <div class="auth__card">
                <div class="auth__brand">Lab Sentry</div>
                <h2 class="auth__title">Register</h2>

                <?php if ($error): ?>
                    <div class="alert alert--error"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert--success"><?php echo htmlspecialchars($success, ENT_QUOTES); ?></div>
                <?php endif; ?>

                <form class="form" method="post" action="modules/auth/register.php" autocomplete="off">
                    <label class="field">
                        <span class="field__label">Username</span>
                        <div class="field__control field__control--icon">
                            <span class="field__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M20 21a8 8 0 1 0-16 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <input class="input" type="text" name="username" placeholder="Buat username" required />
                        </div>
                    </label>

                    <label class="field">
                        <span class="field__label">Password</span>
                        <div class="field__control field__control--icon">
                            <span class="field__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 11V8a5 5 0 0 1 10 0v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M6.5 11h11A1.5 1.5 0 0 1 19 12.5v7A1.5 1.5 0 0 1 17.5 21h-11A1.5 1.5 0 0 1 5 19.5v-7A1.5 1.5 0 0 1 6.5 11Z" stroke="currentColor" stroke-width="1.8"/>
                                </svg>
                            </span>
                            <input class="input" type="password" name="password" placeholder="Buat password" required />
                        </div>
                    </label>

                    <button class="btn btn--purple" type="submit">Register</button>
                </form>

                <p class="auth__hint">Sudah punya akun? <a class="link" href="index.php?page=login">Login</a></p>
            </div>
        </section>

        <section class="auth__panel auth__panel--accent auth__panel--purple">
            <div class="auth__accent">
                <h1 class="auth__accentTitle">Hello, Welcome!</h1>
                <p class="auth__accentText">Daftar untuk mulai mengelola inventaris lab.</p>
            </div>
        </section>
    </main>
</body>
</html>
