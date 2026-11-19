    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <span class="sidebar-logo-frame"><img class="sidebar-logo" src="<?= BASE_URL ?>/images/cmd-logo.png" alt="CDM Mushroom Farm"></span>
            <div class="sidebar-brand-copy">
                <span class="name">CMD</span>
                <span class="sub">Farm Management</span>
            </div>
            <button type="button" class="sidebar-collapse-btn" id="sidebarCollapse" aria-label="Collapse navigation">
                <i data-lucide="panel-left-close"></i>
            </button>
        </div>

        <div class="sidebar-section-title">Overview</div>
        <nav class="sidebar-nav">
            <a href="<?= BASE_URL ?>/dashboard" class="<?= activeNav('dashboard') ?>" title="Dashboard"><span class="icon"><i data-lucide="layout-dashboard"></i></span> Dashboard</a>
        </nav>

        <div class="sidebar-section-title">Farm Operations</div>
        <nav class="sidebar-nav">
            <a href="<?= BASE_URL ?>/production" class="<?= activeNav('production') ?>" title="Production"><span class="icon"><i data-lucide="sprout"></i></span> Production</a>
            <a href="<?= BASE_URL ?>/harvest" class="<?= activeNav('harvest') ?>" title="Harvest"><span class="icon"><i data-lucide="wheat"></i></span> Harvest</a>
            <a href="<?= BASE_URL ?>/processing" class="<?= activeNav('processing') ?>" title="Processing"><span class="icon"><i data-lucide="settings-2"></i></span> Processing</a>
            <a href="<?= BASE_URL ?>/inventory" class="<?= activeNav('inventory') ?>" title="Inventory"><span class="icon"><i data-lucide="boxes"></i></span> Inventory</a>
        </nav>

        <div class="sidebar-section-title">Business Management</div>
        <nav class="sidebar-nav">
            <a href="<?= BASE_URL ?>/sales" class="<?= activeNav('sales') ?>" title="Sales"><span class="icon"><i data-lucide="receipt"></i></span> Sales</a>
            <a href="<?= BASE_URL ?>/delivery" class="<?= activeNav('delivery') ?>" title="Delivery"><span class="icon"><i data-lucide="truck"></i></span> Delivery</a>
            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                <a href="<?= BASE_URL ?>/expense" class="<?= activeNav('expense') ?>" title="Expenses"><span class="icon"><i data-lucide="wallet"></i></span> Expenses</a>
            <?php endif; ?>
        </nav>

        <div class="sidebar-section-title">Administration</div>
        <nav class="sidebar-nav">
            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                <a href="<?= BASE_URL ?>/employee" class="<?= activeNav('employee') ?>" title="Employees"><span class="icon"><i data-lucide="users"></i></span> Employees</a>
                <a href="<?= BASE_URL ?>/report" class="<?= activeNav('report') ?>" title="Reports"><span class="icon"><i data-lucide="chart-no-axes-combined"></i></span> Reports</a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/settings" class="<?= activeNav('settings') ?>" title="Settings"><span class="icon"><i data-lucide="sliders-horizontal"></i></span> Settings</a>
        </nav>
    </aside>

    <button class="sidebar-backdrop" id="sidebarBackdrop" type="button" aria-label="Close navigation menu" tabindex="-1"></button>

    <div class="main-area">
        <header class="topbar">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-outline-secondary d-md-none" id="sidebarToggle" type="button" aria-label="Open navigation menu" aria-controls="sidebar" aria-expanded="false">
                    <i data-lucide="menu"></i>
                </button>
                <div class="topbar-title">
                    <span class="topbar-kicker">CMD FARM MANAGEMENT</span>
                    <h5><?= isset($pageTitle) ? e($pageTitle) : 'Dashboard' ?></h5>
                </div>
            </div>

            <div class="topbar-actions">
                <div class="topbar-search" id="topbarSearch">
                    <i data-lucide="search" aria-hidden="true"></i>
                    <input type="search" id="globalSearch" placeholder="Search pages or this list…" autocomplete="off" aria-label="Search pages or current list" aria-controls="globalSearchResults">
                    <kbd>/</kbd>
                    <div class="search-results" id="globalSearchResults" role="listbox" aria-label="Search results" hidden></div>
                </div>

                <div class="header-popover-wrap">
                    <button class="header-icon-btn notification-trigger" id="notificationToggle" type="button" aria-label="Notifications" aria-expanded="false" aria-controls="notificationPanel">
                        <i data-lucide="bell"></i>
                        <span class="notification-count" id="notificationCount" hidden></span>
                    </button>
                    <section class="header-popover notification-panel" id="notificationPanel" aria-labelledby="notificationHeading" hidden>
                        <div class="popover-heading">
                            <div>
                                <h6 id="notificationHeading">Farm notifications</h6>
                                <span class="popover-caption" id="notificationSummary">Operational updates from your farm</span>
                            </div>
                            <button type="button" class="text-action" id="markNotificationsRead">Mark all read</button>
                        </div>
                        <div class="notification-list" id="notificationList"></div>
                        <div class="notification-history-note">History is kept in this browser for this account.</div>
                    </section>
                </div>

                <button class="header-icon-btn theme-toggle" id="themeToggle" type="button" aria-label="Switch to dark mode" title="Switch theme">
                    <i data-lucide="moon"></i>
                </button>

                <div class="dropdown">
                    <button class="profile-trigger" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="user-avatar"><?= e(strtoupper(substr($_SESSION['employee_name'] ?? 'U', 0, 1))) ?></span>
                        <span class="profile-copy d-none d-sm-block">
                            <strong><?= e($_SESSION['employee_name'] ?? '') ?></strong>
                            <small><?= ($_SESSION['role'] ?? '') === 'admin' ? 'Owner' : 'Employee' ?> · <?= e($_SESSION['position'] ?? '') ?></small>
                        </span>
                        <i class="profile-chevron" data-lucide="chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end profile-menu">
                        <li><div class="profile-menu-heading"><?= e($_SESSION['employee_name'] ?? '') ?><small><?= ($_SESSION['role'] ?? '') === 'admin' ? 'Owner' : 'Employee' ?></small></div></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/settings"><i data-lucide="settings-2"></i> Account settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item profile-logout" href="<?= BASE_URL ?>/auth/logout"><i data-lucide="log-out"></i> Sign out</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="content">
            <?= flashMessage() ?>
