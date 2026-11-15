<div class="page-header">
    <div>
        <h3>Settings</h3>
        <p>Manage your account access and security.</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <section class="card-panel h-100">
            <div class="dashboard-section-heading">
                <h6>Account</h6>
                <span class="section-note"><?= e($accountType) ?></span>
            </div>
            <dl class="settings-account-list mb-0">
                <div><dt>Name</dt><dd><?= e($accountName) ?></dd></div>
                <div><dt>Position</dt><dd><?= e($position) ?></dd></div>
                <div><dt>Access level</dt><dd><?= e($accountType) ?></dd></div>
            </dl>
        </section>
    </div>
    <div class="col-lg-8">
        <section class="card-panel">
            <div class="dashboard-section-heading">
                <h6>Change password</h6>
                <span class="section-note">Use at least 8 characters</span>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/settings/changePassword" class="row g-3">
                <?= csrfField() ?>
                <div class="col-12">
                    <label class="form-label" for="currentPassword">Current password</label>
                    <input type="password" id="currentPassword" name="current_password" class="form-control" autocomplete="current-password" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="newPassword">New password</label>
                    <input type="password" id="newPassword" name="new_password" class="form-control" autocomplete="new-password" minlength="8" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="confirmPassword">Confirm new password</label>
                    <input type="password" id="confirmPassword" name="confirm_password" class="form-control" autocomplete="new-password" minlength="8" required>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-success" type="submit"><i class="bi bi-shield-check me-1"></i> Update password</button>
                </div>
            </form>
        </section>
    </div>
</div>
