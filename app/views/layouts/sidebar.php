    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <span class="sidebar-logo-frame"><img class="sidebar-logo" src="<?= BASE_URL ?>/images/cmd-logo.png" alt="CDM Mushroom Farm"></span>
            <div class="sidebar-brand-copy">
                <span class="name">CMD</span>
                <span class="sub">Farm Management</span>
            </div>
        </div>

        <div class="sidebar-section-title">Overview</div>
        <nav class="sidebar-nav">
            <a href="<?= BASE_URL ?>/dashboard" class="<?= activeNav('dashboard') ?>"><span class="icon"><i data-lucide="layout-dashboard"></i></span> Dashboard</a>
        </nav>

        <div class="sidebar-section-title">Farm Operations</div>
        <nav class="sidebar-nav">
            <a href="<?= BASE_URL ?>/production" class="<?= activeNav('production') ?>"><span class="icon"><i data-lucide="sprout"></i></span> Production</a>
            <a href="<?= BASE_URL ?>/harvest" class="<?= activeNav('harvest') ?>"><span class="icon"><i data-lucide="wheat"></i></span> Harvest</a>
            <a href="<?= BASE_URL ?>/processing" class="<?= activeNav('processing') ?>"><span class="icon"><i data-lucide="settings-2"></i></span> Processing</a>
            <a href="<?= BASE_URL ?>/inventory" class="<?= activeNav('inventory') ?>"><span class="icon"><i data-lucide="boxes"></i></span> Inventory</a>
        </nav>

        <div class="sidebar-section-title">Business Management</div>
        <nav class="sidebar-nav">
            <a href="<?= BASE_URL ?>/sales" class="<?= activeNav('sales') ?>"><span class="icon"><i data-lucide="receipt"></i></span> Sales</a>
            <a href="<?= BASE_URL ?>/delivery" class="<?= activeNav('delivery') ?>"><span class="icon"><i data-lucide="truck"></i></span> Delivery</a>
            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                <a href="<?= BASE_URL ?>/expense" class="<?= activeNav('expense') ?>"><span class="icon"><i data-lucide="wallet"></i></span> Expenses</a>
            <?php endif; ?>
        </nav>

        <div class="sidebar-section-title">Administration</div>
        <nav class="sidebar-nav">
            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                <a href="<?= BASE_URL ?>/employee" class="<?= activeNav('employee') ?>"><span class="icon"><i data-lucide="users"></i></span> Employees</a>
                <a href="<?= BASE_URL ?>/report" class="<?= activeNav('report') ?>"><span class="icon"><i data-lucide="chart-no-axes-combined"></i></span> Reports</a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/settings" class="<?= activeNav('settings') ?>"><span class="icon"><i data-lucide="sliders-horizontal"></i></span> Settings</a>
        </nav>
    </aside>

    <button class="sidebar-backdrop" id="sidebarBackdrop" type="button" aria-label="Close navigation menu" tabindex="-1"></button>

    <div class="main-area">
        <header class="topbar">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-outline-secondary d-md-none" id="sidebarToggle" type="button" aria-label="Open navigation menu" aria-controls="sidebar" aria-expanded="false">
                    <i data-lucide="menu"></i>
                </button>
                <h5><?= isset($pageTitle) ? e($pageTitle) : 'Dashboard' ?></h5>
            </div>
            <div class="user-chip">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-semibold"><?= e($_SESSION['employee_name'] ?? '') ?></div>
                    <div class="user-position"><?= ($_SESSION['role'] ?? '') === 'admin' ? 'Owner' : 'Employee' ?> · <?= e($_SESSION['position'] ?? '') ?></div>
                </div>
                <div class="user-avatar"><?= e(strtoupper(substr($_SESSION['employee_name'] ?? 'U', 0, 1))) ?></div>
                <a href="<?= BASE_URL ?>/auth/logout" class="btn btn-sm btn-outline-danger ms-2" title="Logout" aria-label="Log out"><i data-lucide="log-out"></i></a>
            </div>
        </header>

        <div class="content">
            <?= flashMessage() ?>
