<div class="page-header">
    <div>
        <h3>Expenses</h3>
        <p>This month's total: <strong><?= peso($total) ?></strong></p>
    </div>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-lg"></i> Add Expense</button>
</div>

<div class="card-panel">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>Date</th><th>Category</th><th>Description</th><th>Amount</th><th>Recorded By</th><th></th></tr></thead>
            <tbody>
            <?php if (empty($expenses)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No expenses recorded yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($expenses as $ex): ?>
                <tr>
                    <td><?= niceDate($ex['expense_date']) ?></td>
                    <td><span class="badge text-bg-light border"><?= e($ex['category']) ?></span></td>
                    <td><?= e($ex['description']) ?></td>
                    <td class="fw-semibold text-danger"><?= peso($ex['amount']) ?></td>
                    <td><?= e($ex['recorder_name'] ?? '—') ?></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal<?= $ex['expense_id'] ?>"><i class="bi bi-pencil"></i></button>
                        <form action="<?= BASE_URL ?>/expense/delete/<?= $ex['expense_id'] ?>" method="POST" class="d-inline confirm-delete">
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <div class="modal fade" id="editModal<?= $ex['expense_id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content" action="<?= BASE_URL ?>/expense/update/<?= $ex['expense_id'] ?>" method="POST">
                            <div class="modal-header"><h5 class="modal-title">Edit Expense</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                            <div class="modal-body row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-select">
                                        <?php foreach (['Substrate','Utilities','Labor','Equipment','Transport','Packaging','Rent','Other'] as $c): ?>
                                            <option value="<?= $c ?>" <?= $c==$ex['category']?'selected':'' ?>><?= $c ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6"><label class="form-label">Date</label><input type="date" name="expense_date" class="form-control" value="<?= e($ex['expense_date']) ?>" required></div>
                                <div class="col-md-6"><label class="form-label">Amount (₱)</label><input type="number" step="0.01" name="amount" class="form-control" value="<?= e($ex['amount']) ?>" required></div>
                                <div class="col-md-6">
                                    <label class="form-label">Recorded By</label>
                                    <select name="recorded_by" class="form-select">
                                        <option value="">— None —</option>
                                        <?php foreach ($employees as $emp): ?>
                                            <option value="<?= $emp['employee_id'] ?>" <?= $emp['employee_id']==$ex['recorded_by']?'selected':'' ?>><?= e($emp['first_name'].' '.$emp['last_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2" required><?= e($ex['description']) ?></textarea></div>
                            </div>
                            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-success">Save Changes</button></div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" action="<?= BASE_URL ?>/expense/store" method="POST">
            <div class="modal-header"><h5 class="modal-title">Add Expense</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select"><option>Substrate</option><option>Utilities</option><option>Labor</option><option>Equipment</option><option>Transport</option><option>Packaging</option><option>Rent</option><option>Other</option></select>
                </div>
                <div class="col-md-6"><label class="form-label">Date</label><input type="date" name="expense_date" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
                <div class="col-md-6"><label class="form-label">Amount (₱)</label><input type="number" step="0.01" name="amount" class="form-control" required></div>
                <div class="col-md-6">
                    <label class="form-label">Recorded By</label>
                    <select name="recorded_by" class="form-select">
                        <option value="">— None —</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['employee_id'] ?>"><?= e($emp['first_name'].' '.$emp['last_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2" required></textarea></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-success">Add Expense</button></div>
        </form>
    </div>
</div>
