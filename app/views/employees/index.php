<div class="page-header">
    <div>
        <h3>Employees</h3>
        <p>Manage farm staff accounts and roles.</p>
    </div>
    <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-lg"></i> Add Employee</button>
    <?php endif; ?>
</div>

<div class="card-panel">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>Name</th><th>Username</th><th>Position</th><th>Role</th><th>Contact</th><th>Date Hired</th><th>Status</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($employees as $emp): ?>
                <tr>
                    <td class="fw-semibold"><?= e($emp['first_name'].' '.$emp['last_name']) ?></td>
                    <td><?= e($emp['username']) ?></td>
                    <td><?= e($emp['position']) ?></td>
                    <td><span class="badge text-bg-light border"><?= $emp['role'] === 'admin' ? 'Owner' : 'Employee - ' . e(ucfirst($emp['role'])) ?></span></td>
                    <td><?= e($emp['email']) ?><br><span class="text-muted small"><?= e($emp['phone']) ?></span></td>
                    <td><?= niceDate($emp['date_hired']) ?></td>
                    <td><?= statusBadge($emp['status']) ?></td>
                    <td class="text-end">
                        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal<?= $emp['employee_id'] ?>"><i class="bi bi-pencil"></i></button>
                        <?php endif; ?>
                        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                        <form action="<?= BASE_URL ?>/employee/delete/<?= $emp['employee_id'] ?>" method="POST" class="d-inline confirm-delete">
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php foreach ($employees as $emp): ?>
<div class="modal fade" id="editModal<?= $emp['employee_id'] ?>" tabindex="-1" aria-labelledby="editEmployeeTitle<?= $emp['employee_id'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content" action="<?= BASE_URL ?>/employee/update/<?= $emp['employee_id'] ?>" method="POST">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmployeeTitle<?= $emp['employee_id'] ?>">Edit Employee — <?= e($emp['first_name']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-6"><label class="form-label">First Name</label><input type="text" name="first_name" class="form-control" value="<?= e($emp['first_name']) ?>" required></div>
                <div class="col-md-6"><label class="form-label">Last Name</label><input type="text" name="last_name" class="form-control" value="<?= e($emp['last_name']) ?>" required></div>
                <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= e($emp['email']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="<?= e($emp['phone']) ?>"></div>
                <div class="col-md-6"><label class="form-label">Position</label><input type="text" name="position" class="form-control" value="<?= e($emp['position']) ?>" required></div>
                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <?php foreach (['admin' => 'Owner', 'manager' => 'Manager', 'staff' => 'Staff'] as $r => $label): ?>
                            <option value="<?= $r ?>" <?= $r==$emp['role']?'selected':'' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label">Date Hired</label><input type="date" name="date_hired" class="form-control" value="<?= e($emp['date_hired']) ?>"></div>
                <div class="col-md-4"><label class="form-label">Salary (₱)</label><input type="number" step="0.01" name="salary" class="form-control" value="<?= e($emp['salary']) ?>"></div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" <?= $emp['status']=='active'?'selected':'' ?>>Active</option>
                        <option value="inactive" <?= $emp['status']=='inactive'?'selected':'' ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-12"><label class="form-label">Reset Password <span class="text-muted small">(leave blank to keep current; minimum 8 characters)</span></label><input type="password" name="password" class="form-control" minlength="8" autocomplete="new-password" placeholder="New password"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Save Changes</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" action="<?= BASE_URL ?>/employee/store" method="POST">
            <div class="modal-header"><h5 class="modal-title">Add Employee</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body row g-3">
                <div class="col-md-6"><label class="form-label">First Name</label><input type="text" name="first_name" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Last Name</label><input type="text" name="last_name" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Username</label><input type="text" name="username" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required minlength="8" autocomplete="new-password" placeholder="At least 8 characters"></div>
                <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Position</label><input type="text" name="position" class="form-control" required></div>
                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select"><option value="staff">Staff</option><option value="manager">Manager</option><option value="admin">Admin</option></select>
                </div>
                <div class="col-md-6"><label class="form-label">Date Hired</label><input type="date" name="date_hired" class="form-control" value="<?= date('Y-m-d') ?>"></div>
                <div class="col-md-6"><label class="form-label">Salary (₱)</label><input type="number" step="0.01" name="salary" class="form-control" value="0"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-success">Add Employee</button></div>
        </form>
    </div>
</div>
