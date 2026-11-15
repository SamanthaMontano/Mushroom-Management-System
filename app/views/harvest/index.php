<div class="page-header">
    <div>
        <h3>Harvest Records</h3>
        <p>Log yields collected from each production batch.</p>
    </div>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-lg"></i> Record Harvest</button>
</div>

<div class="card-panel">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>Batch</th><th>Variety</th><th>Harvest Date</th><th>Weight (kg)</th><th>Grade</th><th>Harvested By</th><th></th></tr></thead>
            <tbody>
            <?php if (empty($harvests)): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">No harvest records yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($harvests as $h): ?>
                <tr>
                    <td class="fw-semibold"><?= e($h['batch_code']) ?></td>
                    <td><?= e($h['mushroom_variety']) ?></td>
                    <td><?= niceDate($h['harvest_date']) ?></td>
                    <td><?= number_format($h['weight_kg'],2) ?></td>
                    <td><?= statusBadge($h['grade']) ?></td>
                    <td><?= e($h['harvester_name'] ?? '—') ?></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal<?= $h['harvest_id'] ?>"><i class="bi bi-pencil"></i></button>
                        <form action="<?= BASE_URL ?>/harvest/delete/<?= $h['harvest_id'] ?>" method="POST" class="d-inline confirm-delete">
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <div class="modal fade" id="editModal<?= $h['harvest_id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content" action="<?= BASE_URL ?>/harvest/update/<?= $h['harvest_id'] ?>" method="POST">
                            <div class="modal-header"><h5 class="modal-title">Edit Harvest — <?= e($h['batch_code']) ?></h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                            <div class="modal-body row g-3">
                                <div class="col-md-6"><label class="form-label">Harvest Date</label><input type="date" name="harvest_date" class="form-control" value="<?= e($h['harvest_date']) ?>" required></div>
                                <div class="col-md-6"><label class="form-label">Weight (kg)</label><input type="number" step="0.01" name="weight_kg" class="form-control" value="<?= e($h['weight_kg']) ?>" required></div>
                                <div class="col-md-6">
                                    <label class="form-label">Grade</label>
                                    <select name="grade" class="form-select">
                                        <?php foreach (['A','B','C','Reject'] as $g): ?>
                                            <option value="<?= $g ?>" <?= $g==$h['grade']?'selected':'' ?>><?= $g ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Harvested By</label>
                                    <select name="harvested_by" class="form-select">
                                        <option value="">— None —</option>
                                        <?php foreach ($employees as $emp): ?>
                                            <option value="<?= $emp['employee_id'] ?>" <?= $emp['employee_id']==$h['harvested_by']?'selected':'' ?>><?= e($emp['first_name'].' '.$emp['last_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2"><?= e($h['notes']) ?></textarea></div>
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
        <form class="modal-content" action="<?= BASE_URL ?>/harvest/store" method="POST">
            <div class="modal-header"><h5 class="modal-title">Record Harvest</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">Production Batch</label>
                    <select name="production_id" class="form-select" required>
                        <option value="">Select batch…</option>
                        <?php foreach ($batches as $b): ?>
                            <option value="<?= $b['production_id'] ?>"><?= e($b['batch_code'].' — '.$b['mushroom_variety']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($batches)): ?><div class="form-text text-warning">No batches in Fruiting/Harvesting status. Update a batch's status first.</div><?php endif; ?>
                </div>
                <div class="col-md-6"><label class="form-label">Harvest Date</label><input type="date" name="harvest_date" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
                <div class="col-md-6"><label class="form-label">Weight (kg)</label><input type="number" step="0.01" name="weight_kg" class="form-control" required></div>
                <div class="col-md-6">
                    <label class="form-label">Grade</label>
                    <select name="grade" class="form-select"><option>A</option><option>B</option><option>C</option><option>Reject</option></select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Harvested By</label>
                    <select name="harvested_by" class="form-select">
                        <option value="">— None —</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['employee_id'] ?>"><?= e($emp['first_name'].' '.$emp['last_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-success">Save Harvest</button></div>
        </form>
    </div>
</div>
