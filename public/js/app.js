// CMD - shared front-end behaviors

document.addEventListener('DOMContentLoaded', function () {
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

    const sidebar = document.getElementById('sidebar');
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
