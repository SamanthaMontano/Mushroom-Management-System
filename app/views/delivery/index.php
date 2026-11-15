<div class="page-header">
    <div>
        <h3>Delivery</h3>
        <p>Schedule and track order fulfillment to customers.</p>
    </div>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-lg"></i> New Delivery</button>
</div>

<div class="card-panel">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>Invoice</th><th>Customer</th><th>Address</th><th>Date</th><th>Rider</th><th>Fee</th><th>Status</th><th></th></tr></thead>
            <tbody>
            <?php if (empty($deliveries)): ?>
                <tr><td colspan="8" class="text-center text-muted py-4">No deliveries scheduled yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($deliveries as $d): ?>
                <tr>
                    <td class="fw-semibold"><?= e($d['invoice_no']) ?></td>
                    <td><?= e($d['customer_name']) ?></td>
                    <td style="max-width:220px;"><?= e($d['delivery_address']) ?></td>
                    <td><?= niceDate($d['delivery_date']) ?></td>
                    <td><?= e($d['rider_name']) ?></td>
                    <td><?= peso($d['delivery_fee']) ?></td>
                    <td><?= statusBadge($d['delivery_status']) ?></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal<?= $d['delivery_id'] ?>"><i class="bi bi-pencil"></i></button>
                        <form action="<?= BASE_URL ?>/delivery/delete/<?= $d['delivery_id'] ?>" method="POST" class="d-inline confirm-delete">
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <div class="modal fade" id="editModal<?= $d['delivery_id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content" action="<?= BASE_URL ?>/delivery/update/<?= $d['delivery_id'] ?>" method="POST">
                            <div class="modal-header"><h5 class="modal-title">Edit Delivery — <?= e($d['invoice_no']) ?></h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                            <div class="modal-body row g-3">
                                <div class="col-12"><label class="form-label">Delivery Address</label><textarea name="delivery_address" class="form-control" rows="2" required><?= e($d['delivery_address']) ?></textarea></div>
                                <div class="col-md-6"><label class="form-label">Delivery Date</label><input type="date" name="delivery_date" class="form-control" value="<?= e($d['delivery_date']) ?>"></div>
                                <div class="col-md-6"><label class="form-label">Delivery Fee (₱)</label><input type="number" step="0.01" name="delivery_fee" class="form-control" value="<?= e($d['delivery_fee']) ?>"></div>
                                <div class="col-md-6"><label class="form-label">Rider Name</label><input type="text" name="rider_name" class="form-control" value="<?= e($d['rider_name']) ?>"></div>
                                <div class="col-md-6"><label class="form-label">Contact Number</label><input type="text" name="contact_number" class="form-control" value="<?= e($d['contact_number']) ?>"></div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select name="delivery_status" class="form-select">
                                        <?php foreach (['Pending','In Transit','Delivered','Cancelled'] as $st): ?>
                                            <option value="<?= $st ?>" <?= $st==$d['delivery_status']?'selected':'' ?>><?= $st ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2"><?= e($d['notes']) ?></textarea></div>
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
        <form class="modal-content" action="<?= BASE_URL ?>/delivery/store" method="POST">
            <div class="modal-header"><h5 class="modal-title">New Delivery</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">Sale / Invoice</label>
                    <select name="sale_id" class="form-select" required>
                        <option value="">Select sale…</option>
                        <?php foreach ($sales as $s): ?>
                            <option value="<?= $s['sale_id'] ?>"><?= e($s['invoice_no'].' — '.$s['customer_name'].' ('.$s['product_name'].')') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12"><label class="form-label">Delivery Address</label><textarea name="delivery_address" class="form-control" rows="2" required></textarea></div>
                <div class="col-md-6"><label class="form-label">Delivery Date</label><input type="date" name="delivery_date" class="form-control" value="<?= date('Y-m-d') ?>"></div>
                <div class="col-md-6"><label class="form-label">Delivery Fee (₱)</label><input type="number" step="0.01" name="delivery_fee" class="form-control" value="0"></div>
                <div class="col-md-6"><label class="form-label">Rider Name</label><input type="text" name="rider_name" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Contact Number</label><input type="text" name="contact_number" class="form-control"></div>
                <div class="col-12">
                    <label class="form-label">Status</label>
                    <select name="delivery_status" class="form-select"><option>Pending</option><option>In Transit</option><option>Delivered</option><option>Cancelled</option></select>
                </div>
                <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-success">Save Delivery</button></div>
        </form>
    </div>
</div>
