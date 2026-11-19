<div class="page-header">
    <div>
        <h3>Processing</h3>
        <p>Convert raw harvest into fresh-packed, dried, or powdered product.</p>
    </div>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-lg"></i> New Process</button>
</div>

<div class="card-panel">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>Batch</th><th>Type</th><th>Input (kg)</th><th>Output</th><th>Date</th><th>Processed By</th><th>Status</th><th></th></tr></thead>
            <tbody>
            <?php if (empty($records)): ?>
                <tr><td colspan="8" class="text-center text-muted py-4">No processing records yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($records as $r): ?>
                <tr>
                    <td class="fw-semibold"><?= e($r['batch_code']) ?></td>
                    <td><?= e($r['process_type']) ?></td>
                    <td><?= number_format($r['input_weight_kg'],2) ?></td>
                    <td><?= number_format($r['output_weight_kg'],2) ?> <?= e($r['output_unit']) ?></td>
                    <td><?= niceDate($r['process_date']) ?></td>
                    <td><?= e($r['processor_name'] ?? '—') ?></td>
                    <td><?= statusBadge($r['status']) ?></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal<?= $r['processing_id'] ?>"><i class="bi bi-pencil"></i></button>
                        <form action="<?= BASE_URL ?>/processing/delete/<?= $r['processing_id'] ?>" method="POST" class="d-inline confirm-delete">
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php foreach ($records as $r): ?>
<div class="modal fade" id="editModal<?= $r['processing_id'] ?>" tabindex="-1" aria-labelledby="editProcessTitle<?= $r['processing_id'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="<?= BASE_URL ?>/processing/update/<?= $r['processing_id'] ?>" method="POST">
            <div class="modal-header">
                <h5 class="modal-title" id="editProcessTitle<?= $r['processing_id'] ?>">Edit Process — <?= e($r['batch_code']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Process Type</label>
                    <select name="process_type" class="form-select">
                        <?php foreach (['Fresh Pack','Dried','Powdered','Marinated','Other'] as $t): ?>
                            <option value="<?= $t ?>" <?= $t==$r['process_type']?'selected':'' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">Process Date</label><input type="date" name="process_date" class="form-control" value="<?= e($r['process_date']) ?>" required></div>
                <div class="col-md-4"><label class="form-label">Input Weight (kg)</label><input type="number" step="0.01" name="input_weight_kg" class="form-control" value="<?= e($r['input_weight_kg']) ?>"></div>
                <div class="col-md-4"><label class="form-label">Output Weight</label><input type="number" step="0.01" name="output_weight_kg" class="form-control" value="<?= e($r['output_weight_kg']) ?>"></div>
                <div class="col-md-4"><label class="form-label">Output Unit</label><input type="text" name="output_unit" class="form-control" value="<?= e($r['output_unit']) ?>"></div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <?php foreach (['In Progress','Completed'] as $s): ?>
                            <option value="<?= $s ?>" <?= $s==$r['status']?'selected':'' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Processed By</label>
                    <select name="processed_by" class="form-select">
                        <option value="">— None —</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['employee_id'] ?>" <?= $emp['employee_id']==$r['processed_by']?'selected':'' ?>><?= e($emp['first_name'].' '.$emp['last_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2"><?= e($r['notes']) ?></textarea></div>
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
    <div class="modal-dialog">
        <form class="modal-content" action="<?= BASE_URL ?>/processing/store" method="POST">
            <div class="modal-header"><h5 class="modal-title">New Processing Record</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">Harvest Record</label>
                    <select name="harvest_id" class="form-select" required>
                        <option value="">Select harvest…</option>
                        <?php foreach ($harvests as $h): ?>
                            <option value="<?= $h['harvest_id'] ?>"><?= e($h['batch_code'].' — '.niceDate($h['harvest_date']).' ('.$h['weight_kg'].' kg)') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Process Type</label>
                    <select name="process_type" class="form-select"><option>Fresh Pack</option><option>Dried</option><option>Powdered</option><option>Marinated</option><option>Other</option></select>
                </div>
                <div class="col-md-6"><label class="form-label">Process Date</label><input type="date" name="process_date" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
                <div class="col-md-4"><label class="form-label">Input Weight (kg)</label><input type="number" step="0.01" name="input_weight_kg" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">Output Weight</label><input type="number" step="0.01" name="output_weight_kg" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">Output Unit</label><input type="text" name="output_unit" class="form-control" value="kg"></div>
                <div class="col-md-6">
                    <label class="form-label">Processed By</label>
                    <select name="processed_by" class="form-select">
                        <option value="">— None —</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['employee_id'] ?>"><?= e($emp['first_name'].' '.$emp['last_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select"><option>Completed</option><option>In Progress</option></select>
                </div>
                <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-success">Save Record</button></div>
        </form>
    </div>
</div>
