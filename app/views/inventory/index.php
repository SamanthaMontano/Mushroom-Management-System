<div class="page-header">
    <div>
        <h3>Inventory</h3>
        <p>Raw materials, spawn, packaging, and supplies on hand.</p>
    </div>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-lg"></i> Add Item</button>
</div>

<div class="card-panel">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>Item</th><th>Category</th><th>Quantity</th><th>Reorder Level</th><th>Unit Cost</th><th>Supplier</th><th>Last Restocked</th><th>Status</th><th></th></tr></thead>
            <tbody>
            <?php if (empty($items)): ?>
                <tr><td colspan="9" class="text-center text-muted py-4">No inventory items yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($items as $i): ?>
                <tr>
                    <td class="fw-semibold"><?= e($i['item_name']) ?></td>
                    <td><?= e($i['category']) ?></td>
                    <td><?= number_format($i['quantity'],2) ?> <?= e($i['unit']) ?></td>
                    <td><?= number_format($i['reorder_level'],2) ?> <?= e($i['unit']) ?></td>
                    <td><?= peso($i['unit_cost']) ?></td>
                    <td><?= e($i['supplier']) ?></td>
                    <td><?= niceDate($i['last_restocked']) ?></td>
                    <td><?= statusBadge($i['status']) ?></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal<?= $i['inventory_id'] ?>"><i class="bi bi-pencil"></i></button>
                        <form action="<?= BASE_URL ?>/inventory/delete/<?= $i['inventory_id'] ?>" method="POST" class="d-inline confirm-delete">
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <div class="modal fade" id="editModal<?= $i['inventory_id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content" action="<?= BASE_URL ?>/inventory/update/<?= $i['inventory_id'] ?>" method="POST">
                            <div class="modal-header"><h5 class="modal-title">Edit Item — <?= e($i['item_name']) ?></h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                            <div class="modal-body row g-3">
                                <div class="col-md-6"><label class="form-label">Item Name</label><input type="text" name="item_name" class="form-control" value="<?= e($i['item_name']) ?>" required></div>
                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-select">
                                        <?php foreach (['Substrate','Spawn','Packaging','Equipment','Supplies','Other'] as $c): ?>
                                            <option value="<?= $c ?>" <?= $c==$i['category']?'selected':'' ?>><?= $c ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4"><label class="form-label">Quantity</label><input type="number" step="0.01" name="quantity" class="form-control" value="<?= e($i['quantity']) ?>" required></div>
                                <div class="col-md-4"><label class="form-label">Unit</label><input type="text" name="unit" class="form-control" value="<?= e($i['unit']) ?>" required></div>
                                <div class="col-md-4"><label class="form-label">Reorder Level</label><input type="number" step="0.01" name="reorder_level" class="form-control" value="<?= e($i['reorder_level']) ?>"></div>
                                <div class="col-md-6"><label class="form-label">Unit Cost (₱)</label><input type="number" step="0.01" name="unit_cost" class="form-control" value="<?= e($i['unit_cost']) ?>"></div>
                                <div class="col-md-6"><label class="form-label">Supplier</label><input type="text" name="supplier" class="form-control" value="<?= e($i['supplier']) ?>"></div>
                                <div class="col-md-6"><label class="form-label">Last Restocked</label><input type="date" name="last_restocked" class="form-control" value="<?= e($i['last_restocked']) ?>"></div>
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
        <form class="modal-content" action="<?= BASE_URL ?>/inventory/store" method="POST">
            <div class="modal-header"><h5 class="modal-title">Add Inventory Item</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body row g-3">
                <div class="col-md-6"><label class="form-label">Item Name</label><input type="text" name="item_name" class="form-control" required></div>
                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select"><option>Substrate</option><option>Spawn</option><option>Packaging</option><option>Equipment</option><option>Supplies</option><option>Other</option></select>
                </div>
                <div class="col-md-4"><label class="form-label">Quantity</label><input type="number" step="0.01" name="quantity" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">Unit</label><input type="text" name="unit" class="form-control" value="kg" required></div>
                <div class="col-md-4"><label class="form-label">Reorder Level</label><input type="number" step="0.01" name="reorder_level" class="form-control" value="0"></div>
                <div class="col-md-6"><label class="form-label">Unit Cost (₱)</label><input type="number" step="0.01" name="unit_cost" class="form-control" value="0"></div>
                <div class="col-md-6"><label class="form-label">Supplier</label><input type="text" name="supplier" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Last Restocked</label><input type="date" name="last_restocked" class="form-control" value="<?= date('Y-m-d') ?>"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-success">Add Item</button></div>
        </form>
    </div>
</div>
