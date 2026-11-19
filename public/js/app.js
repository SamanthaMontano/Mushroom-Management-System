// CMD - shared front-end behaviors

document.addEventListener('DOMContentLoaded', function () {
    const root = document.documentElement;
    const themeToggle = document.getElementById('themeToggle');
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const sidebar = document.getElementById('sidebar');

    function setTheme(theme) {
        const isDark = theme === 'dark';
        root.dataset.theme = isDark ? 'dark' : 'light';
        localStorage.setItem('cmd-theme', isDark ? 'dark' : 'light');

        if (themeToggle) {
            themeToggle.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
            themeToggle.setAttribute('title', isDark ? 'Switch to light mode' : 'Switch to dark mode');
            themeToggle.innerHTML = '<i data-lucide="' + (isDark ? 'sun' : 'moon') + '"></i>';
            if (window.lucide) window.lucide.createIcons();
        }
        document.dispatchEvent(new CustomEvent('cmd:themechange', { detail: { theme: isDark ? 'dark' : 'light' } }));
    }

    setTheme(root.dataset.theme);
    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            setTheme(root.dataset.theme === 'dark' ? 'light' : 'dark');
        });
    }

    if (sidebar) {
        const isCollapsed = localStorage.getItem('cmd-sidebar-collapsed') === 'true';
        root.classList.toggle('sidebar-collapsed', isCollapsed);
        if (sidebarCollapse) {
            sidebarCollapse.setAttribute('aria-label', isCollapsed ? 'Expand navigation' : 'Collapse navigation');
            sidebarCollapse.innerHTML = '<i data-lucide="' + (isCollapsed ? 'panel-left-open' : 'panel-left-close') + '"></i>';
            if (window.lucide) window.lucide.createIcons();
            sidebarCollapse.addEventListener('click', function () {
                const collapsed = root.classList.toggle('sidebar-collapsed');
                localStorage.setItem('cmd-sidebar-collapsed', String(collapsed));
                sidebarCollapse.setAttribute('aria-label', collapsed ? 'Expand navigation' : 'Collapse navigation');
                sidebarCollapse.innerHTML = '<i data-lucide="' + (collapsed ? 'panel-left-open' : 'panel-left-close') + '"></i>';
                if (window.lucide) window.lucide.createIcons();
            });
        }
    }

    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('passwordToggle');

    if (passwordInput && passwordToggle) {
        passwordToggle.addEventListener('click', function () {
            const isVisible = passwordInput.type === 'text';
            passwordInput.type = isVisible ? 'password' : 'text';
            passwordToggle.setAttribute('aria-pressed', String(!isVisible));
            passwordToggle.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
            passwordToggle.innerHTML = '<i class="bi ' + (isVisible ? 'bi-eye' : 'bi-eye-slash') + '" aria-hidden="true"></i>';
            passwordInput.focus();
        });
    }

    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');

    function setSidebarOpen(isOpen) {
        if (!sidebar || !sidebarToggle) return;
        sidebar.classList.toggle('show', isOpen);
        document.body.classList.toggle('sidebar-open', isOpen);
        sidebarToggle.setAttribute('aria-expanded', String(isOpen));
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            setSidebarOpen(!sidebar.classList.contains('show'));
        });
    }
    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener('click', function () {
            setSidebarOpen(false);
        });
    }
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') setSidebarOpen(false);
    });
    if (sidebar) {
        sidebar.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.matchMedia('(max-width: 767.98px)').matches) setSidebarOpen(false);
            });
        });
    }
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) setSidebarOpen(false);
    });

    const searchInput = document.getElementById('globalSearch');
    const searchWrap = document.getElementById('topbarSearch');
    const searchResults = document.getElementById('globalSearchResults');
    const navLinks = sidebar ? Array.from(sidebar.querySelectorAll('.sidebar-nav a')) : [];
    const searchableRows = Array.from(document.querySelectorAll('.table tbody tr'));

    function updateSearch() {
        if (!searchInput || !searchResults) return;
        const query = searchInput.value.trim().toLocaleLowerCase();
        let visibleRows = 0;
        searchableRows.forEach(function (row) {
            const matches = !query || row.textContent.toLocaleLowerCase().includes(query);
            row.hidden = !matches;
            if (matches && !row.querySelector('td[colspan]')) visibleRows++;
        });

        searchResults.replaceChildren();
        if (!query) {
            searchResults.hidden = true;
            return;
        }

        const matchingLinks = navLinks.filter(function (link) {
            return link.textContent.trim().toLocaleLowerCase().includes(query);
        }).slice(0, 6);
        const heading = document.createElement('div');
        heading.className = 'search-results-label';
        heading.textContent = matchingLinks.length ? 'Pages' : 'Search';
        searchResults.appendChild(heading);

        matchingLinks.forEach(function (link) {
            const result = document.createElement('a');
            result.className = 'search-result-link';
            result.href = link.href;
            result.innerHTML = '<i data-lucide="arrow-up-right"></i>';
            const label = document.createElement('span');
            label.textContent = link.textContent.trim();
            result.prepend(label);
            searchResults.appendChild(result);
        });

        if (searchableRows.length) {
            const hint = document.createElement('div');
            hint.className = 'search-result-hint';
            hint.textContent = visibleRows + (visibleRows === 1 ? ' matching row' : ' matching rows') + ' on this page';
            searchResults.appendChild(hint);
        } else if (!matchingLinks.length) {
            const empty = document.createElement('div');
            empty.className = 'search-result-hint';
            empty.textContent = 'No matching pages';
            searchResults.appendChild(empty);
        }

        searchResults.hidden = false;
        if (window.lucide) window.lucide.createIcons({ attrs: { 'aria-hidden': 'true' } });
    }

    if (searchInput && searchWrap && searchResults) {
        searchInput.addEventListener('input', updateSearch);
        searchInput.addEventListener('focus', updateSearch);
        searchInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                const firstResult = searchResults.querySelector('.search-result-link');
                if (firstResult) window.location.href = firstResult.href;
            }
            if (event.key === 'Escape') {
                searchResults.hidden = true;
                searchInput.blur();
            }
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === '/' && !/input|textarea|select/i.test(document.activeElement.tagName)) {
                event.preventDefault();
                searchInput.focus();
            }
        });
        document.addEventListener('click', function (event) {
            if (!searchWrap.contains(event.target)) searchResults.hidden = true;
        });
    }

    const notificationToggle = document.getElementById('notificationToggle');
    const notificationPanel = document.getElementById('notificationPanel');
    const notificationList = document.getElementById('notificationList');
    const notificationCount = document.getElementById('notificationCount');
    const notificationSummary = document.getElementById('notificationSummary');
    const markNotificationsRead = document.getElementById('markNotificationsRead');

    if (notificationToggle && notificationPanel && notificationList && notificationCount) {
        const storageKey = 'cmd-notifications-' + (window.cmdNotificationUserId || 'guest');
        let history = JSON.parse(localStorage.getItem(storageKey) || '[]');
        const activeNotifications = window.cmdNotifications || [];
        const knownIds = new Set(history.map(function (item) { return item.id; }));
        const activeIds = new Set(activeNotifications.map(function (item) { return item.notification_id; }));
        activeNotifications.forEach(function (item) {
            if (!knownIds.has(item.notification_id)) {
                history.unshift({
                    id: item.notification_id,
                    type: item.type,
                    title: item.title,
                    detail: item.detail,
                    occurredAt: item.occurred_at,
                    firstSeenAt: new Date().toISOString(),
                    read: false
                });
            } else {
                const existing = history.find(function (entry) { return entry.id === item.notification_id; });
                if (existing) {
                    existing.title = item.title;
                    existing.detail = item.detail;
                    existing.occurredAt = item.occurred_at;
                }
            }
        });
        history.forEach(function (item) {
            item.resolved = !activeIds.has(item.id);
        });
        history = history.slice(0, 50);

        function renderNotifications() {
            localStorage.setItem(storageKey, JSON.stringify(history));
            const unread = history.filter(function (item) { return !item.read; }).length;
            notificationCount.textContent = unread > 9 ? '9+' : String(unread);
            notificationCount.hidden = unread === 0;
            notificationSummary.textContent = unread ? unread + ' unread update' + (unread === 1 ? '' : 's') : 'You’re all caught up';
            notificationList.replaceChildren();

            if (!history.length) {
                const empty = document.createElement('div');
                empty.className = 'notification-empty';
                empty.textContent = 'No farm alerts right now. New operational updates will appear here.';
                notificationList.appendChild(empty);
                return;
            }

            const destinations = { inventory: '/inventory', harvest: '/production', delivery: '/delivery' };
            const icons = { inventory: 'boxes', harvest: 'sprout', delivery: 'truck' };
            history.forEach(function (item) {
                const link = document.createElement('a');
                link.className = 'notification-item' + (item.read ? '' : ' is-unread') + (item.resolved ? ' is-resolved' : '');
                link.href = (window.cmdBaseUrl || '') + (destinations[item.type] || '/dashboard');

                const icon = document.createElement('span');
                icon.className = 'notification-icon notification-icon--' + item.type;
                icon.innerHTML = '<i data-lucide="' + (icons[item.type] || 'bell') + '"></i>';
                const copy = document.createElement('span');
                copy.className = 'notification-copy';
                const title = document.createElement('strong');
                title.textContent = item.title;
                const detail = document.createElement('small');
                detail.textContent = item.detail;
                const meta = document.createElement('small');
                meta.className = 'notification-time';
                const timestamp = String(item.occurredAt || item.firstSeenAt);
                const date = /^\d{4}-\d{2}-\d{2}/.test(timestamp)
                    ? new Date(timestamp.slice(0, 10) + 'T12:00:00')
                    : new Date(timestamp);
                meta.textContent = (item.resolved ? 'Resolved · ' : '') + new Intl.DateTimeFormat(undefined, { month: 'short', day: 'numeric' }).format(date);
                copy.append(title, detail, meta);
                link.append(icon, copy);
                link.addEventListener('click', function () {
                    item.read = true;
                    localStorage.setItem(storageKey, JSON.stringify(history));
                    const unread = history.filter(function (entry) { return !entry.read; }).length;
                    notificationCount.textContent = unread > 9 ? '9+' : String(unread);
                    notificationCount.hidden = unread === 0;
                    notificationSummary.textContent = unread ? unread + ' unread update' + (unread === 1 ? '' : 's') : 'You’re all caught up';
                });
                notificationList.appendChild(link);
            });
            if (window.lucide) window.lucide.createIcons({ attrs: { 'aria-hidden': 'true' } });
        }

        renderNotifications();
        notificationToggle.addEventListener('click', function (event) {
            event.stopPropagation();
            const isOpen = notificationPanel.hidden;
            notificationPanel.hidden = !isOpen;
            notificationToggle.setAttribute('aria-expanded', String(isOpen));
            if (isOpen) renderNotifications();
        });
        markNotificationsRead.addEventListener('click', function () {
            history.forEach(function (item) { item.read = true; });
            renderNotifications();
        });
        document.addEventListener('click', function (event) {
            if (!notificationPanel.contains(event.target) && !notificationToggle.contains(event.target)) {
                notificationPanel.hidden = true;
                notificationToggle.setAttribute('aria-expanded', 'false');
            }
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                notificationPanel.hidden = true;
                notificationToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Confirm before any destructive delete form submits
    document.querySelectorAll('form.confirm-delete').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            const msg = form.dataset.confirm || 'Are you sure you want to delete this record?';
            if (!confirm(msg)) e.preventDefault();
        });
    });

    // Auto-dismiss alerts after 5s
    document.querySelectorAll('.alert').forEach(function (alertEl) {
        setTimeout(function () {
            const alert = bootstrap.Alert.getOrCreateInstance(alertEl);
            if (alert) alert.close();
        }, 5000);
    });
});
