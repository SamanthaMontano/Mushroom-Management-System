<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign in - <?= e(APP_NAME) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body class="login-body">
<main class="login-layout">
    <section class="login-visual" aria-label="Mushroom farm">
        <img class="login-farm-image" src="<?= BASE_URL ?>/images/mushroom-farm.jpg" alt="Mushrooms growing on logs inside a cultivation farm">
        <div class="login-visual-shade"></div>
        <div class="login-visual-content">
            <a class="login-visual-brand" href="<?= BASE_URL ?>/auth/login" aria-label="CMD sign in">
                <span class="login-logo-frame"><img class="login-visual-logo" src="<?= BASE_URL ?>/images/cmd-logo.png" alt="CDM Mushroom Farm"></span>
                <span class="login-visual-brand-copy">
                    <strong>CMD</strong>
                    <small>FARM MANAGEMENT SYSTEM</small>
                </span>
            </a>
            <div class="login-visual-copy">
                <span class="login-eyebrow"><i class="bi bi-sunrise"></i> GROWN WITH PURPOSE</span>
                <h1>CMD Farm Management</h1>
                <p>Cultivating Growth Through Smarter Farm Management.</p>
            </div>
            <a class="login-photo-credit" href="https://unsplash.com/s/photos/mushroom-farm" target="_blank" rel="noopener noreferrer">
                CDM Mushroom · Valladolid, Negros Occidental
            </a>
        </div>
    </section>

    <section class="login-panel">
        <div class="login-form-wrap">
            <div class="login-mobile-brand">
                <span class="login-logo-frame"><img class="login-visual-logo" src="<?= BASE_URL ?>/images/cmd-logo.png" alt="CDM Mushroom Farm"></span>
                <span class="login-visual-brand-copy">
                    <strong>CMD</strong>
                    <small>FARM MANAGEMENT SYSTEM</small>
                </span>
            </div>
            <div class="login-form-heading">
                <span class="login-form-kicker">WELCOME TO CMD</span>
                <h2>Sign in to your account</h2>
                <p>Choose your account type, then enter your credentials.</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2" role="alert"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/auth/login">
                <fieldset class="login-role-fieldset">
                    <legend class="form-label">I am signing in as</legend>
                    <div class="login-role-options">
                        <label class="login-role-option">
                            <input type="radio" name="login_type" value="owner" required <?= ($selectedLoginType ?? '') === 'owner' ? 'checked' : '' ?>>
                            <span class="login-role-content">
                                <span class="login-role-icon"><i class="bi bi-building"></i></span>
                                <span class="login-role-text"><strong>Owner</strong><small>Business overview</small></span>
                                <i class="bi bi-check-circle-fill login-role-check" aria-hidden="true"></i>
                            </span>
                        </label>
                        <label class="login-role-option">
                            <input type="radio" name="login_type" value="employee" required <?= ($selectedLoginType ?? '') === 'employee' ? 'checked' : '' ?>>
                            <span class="login-role-content">
                                <span class="login-role-icon"><i class="bi bi-person-workspace"></i></span>
                                <span class="login-role-text"><strong>Employee</strong><small>Farm operations</small></span>
                                <i class="bi bi-check-circle-fill login-role-check" aria-hidden="true"></i>
                            </span>
                        </label>
                    </div>
                </fieldset>

                <div class="mb-3">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required autofocus autocomplete="username" placeholder="Enter your username" value="<?= e($_POST['username'] ?? '') ?>">
                </div>
                <div class="mb-4">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group login-password-group">
                        <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password" placeholder="Enter your password">
                        <button class="btn login-password-toggle" type="button" id="passwordToggle" aria-label="Show password" aria-pressed="false" aria-controls="password">
                            <i class="bi bi-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-success w-100 login-submit">Sign in <i class="bi bi-arrow-right ms-1"></i></button>
            </form>

            <div class="login-access-note">
                <i class="bi bi-shield-lock" aria-hidden="true"></i>
                <span>Your account type is verified with your username and password.</span>
            </div>
            <p class="login-hint">Need access? Ask your farm owner for an account.</p>
        </div>
    </section>
</main>
<script src="<?= BASE_URL ?>/js/app.js"></script>
</body>
</html>
