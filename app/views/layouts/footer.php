        </div>
        <footer class="app-footer text-center py-3">
            &copy; <?= date('Y') ?> CMD — Cultivation &amp; Mushroom Data Management System. All rights reserved.
        </footer>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script>
<script>
    window.cmdNotifications = <?= json_encode($notifications ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
    window.cmdNotificationUserId = <?= (int) ($notificationUserId ?? 0) ?>;
    window.cmdBaseUrl = <?= json_encode(BASE_URL, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
    if (window.lucide) window.lucide.createIcons();
</script>
<script src="<?= BASE_URL ?>/js/app.js"></script>
</body>
</html>
