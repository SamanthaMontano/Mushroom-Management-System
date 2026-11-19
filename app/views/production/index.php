<div class="page-header">
    <div>
        <h3>Production Batches</h3>
        <p>Track cultivation cycles from inoculation to harvest.</p>
    </div>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-lg"></i> New Batch</button>
</div>

<div class="card-panel">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Batch Code</th><th>Variety</th><th>Substrate</th><th>Bags</th>
                    <th>Inoculated</th><th>Expected Harvest</th><th>Location</th>
                    <th>Supervisor</th><th>Status</th><th></th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($batches)): ?>
                <tr><td colspan="10" class="text-center text-muted py-4">No production batches yet. Click "New Batch" to add one.</td></tr>
            <?php endif; ?>
            <?php foreach ($batches as $b): ?>
                <tr>
                    <td class="fw-semibold"><?= e($b['batch_code']) ?></td>
                    <td><?= e($b['mushroom_variety']) ?></td>
                    <td><?= e($b['substrate_type']) ?></td>
                    <td><?= (int)$b['bag_quantity'] ?></td>
                    <td><?= niceDate($b['inoculation_date']) ?></td>
                    <td><?= niceDate($b['expected_harvest_date']) ?></td>
                    <td><?= e($b['growing_location']) ?></td>
                    <td><?= e($b['employee_name'] ?? '—') ?></td>
                    <td><?= statusBadge($b['status']) ?></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal<?= $b['production_id'] ?>"><i class="bi bi-pencil"></i></button>
                        <form action="<?= BASE_URL ?>/production/delete/<?= $b['production_id'] ?>" method="POST" class="d-inline confirm-delete" data-confirm="Delete batch <?= e($b['batch_code']) ?>?">
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>

            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php foreach ($batches as $b): ?>
<div class="modal fade" id="editModal<?= $b['production_id'] ?>" tabindex="-1" aria-labelledby="editBatchTitle<?= $b['production_id'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content" action="<?= BASE_URL ?>/production/update/<?= $b['production_id'] ?>" method="POST">
            <div class="modal-header">
                <h5 class="modal-title" id="editBatchTitle<?= $b['production_id'] ?>">Edit Batch — <?= e($b['batch_code']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Mushroom Variety</label>
                    <input type="text" name="mushroom_variety" class="form-control" value="<?= e($b['mushroom_variety']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Substrate Type</label>
                    <input type="text" name="substrate_type" class="form-control" value="<?= e($b['substrate_type']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Bag Quantity</label>
                    <input type="number" name="bag_quantity" class="form-control" value="<?= (int)$b['bag_quantity'] ?>" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Inoculation Date</label>
                    <input type="date" name="inoculation_date" class="form-control" value="<?= e($b['inoculation_date']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Expected Harvest</label>
                    <input type="date" name="expected_harvest_date" class="form-control" value="<?= e($b['expected_harvest_date']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Growing Location</label>
                    <input type="text" name="growing_location" class="form-control" value="<?= e($b['growing_location']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Supervisor</label>
                    <select name="responsible_employee_id" class="form-select">
                        <option value="">— None —</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['employee_id'] ?>" <?= $emp['employee_id']==$b['responsible_employee_id']?'selected':'' ?>><?= e($emp['first_name'].' '.$emp['last_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <?php foreach (['Incubation','Fruiting','Harvesting','Completed','Failed'] as $st): ?>
                            <option value="<?= $st ?>" <?= $st==$b['status']?'selected':'' ?>><?= $st ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"><?= e($b['notes']) ?></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Save Changes</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" action="<?= BASE_URL ?>/production/store" method="POST">
            <div class="modal-header"><h5 class="modal-title">New Production Batch</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Batch Code (auto-generated)</label>
                    <input type="text" class="form-control" value="<?= e($nextCode) ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mushroom Variety</label>
                    <input type="text" name="mushroom_variety" class="form-control" value="Oyster Mushroom" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Substrate Type</label>
                    <input type="text" name="substrate_type" class="form-control" placeholder="e.g. Sawdust + Rice Bran">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bag Quantity</label>
                    <input type="number" name="bag_quantity" class="form-control" min="0" value="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Inoculation Date</label>
                    <input type="date" name="inoculation_date" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Expected Harvest</label>
                    <input type="date" name="expected_harvest_date" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Growing Location</label>
                    <input type="text" name="growing_location" class="form-control" placeholder="e.g. Grow House A">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Supervisor</label>
                    <select name="responsible_employee_id" class="form-select">
                        <option value="">— None —</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['employee_id'] ?>"><?= e($emp['first_name'].' '.$emp['last_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success">Add Batch</button>
            </div>
        </form>
    </div>
</div>
