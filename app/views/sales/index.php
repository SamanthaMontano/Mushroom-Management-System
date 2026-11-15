<div class="page-header">
    <div>
        <h3>Sales</h3>
        <p>Record and track mushroom product sales.</p>
    </div>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-lg"></i> New Sale</button>
</div>

<div class="card-panel">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>Invoice</th><th>Customer</th><th>Product</th><th>Qty</th><th>Unit Price</th><th>Total</th><th>Date</th><th>Payment</th><th>Sold By</th><th>Actions</th></tr></thead>
            <tbody>
            <?php if (empty($sales)): ?>
                <tr><td colspan="10" class="text-center text-muted py-4">No sales recorded yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($sales as $s): ?>
                <tr>
                    <td class="fw-semibold"><?= e($s['invoice_no']) ?></td>
                    <td><?= e($s['customer_name']) ?></td>
                    <td><?= e($s['product_name']) ?></td>
                    <td><?= number_format($s['quantity'],2) ?> <?= e($s['unit']) ?></td>
                    <td><?= peso($s['unit_price']) ?></td>
                    <td class="fw-semibold"><?= peso($s['total_amount']) ?></td>
                    <td><?= niceDate($s['sale_date']) ?></td>
                    <td><?= statusBadge($s['payment_status']) ?></td>
                    <td><?= e($s['seller_name'] ?? '—') ?></td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-success" href="<?= BASE_URL ?>/sales/receipt/<?= (int) $s['sale_id'] ?>" target="_blank" rel="noopener noreferrer" aria-label="Print receipt for <?= e($s['invoice_no']) ?>" title="Print receipt"><i class="bi bi-printer"></i></a>
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal<?= $s['sale_id'] ?>"><i class="bi bi-pencil"></i></button>
                        <form action="<?= BASE_URL ?>/sales/delete/<?= $s['sale_id'] ?>" method="POST" class="d-inline confirm-delete">
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <div class="modal fade" id="editModal<?= $s['sale_id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <form class="modal-content" action="<?= BASE_URL ?>/sales/update/<?= $s['sale_id'] ?>" method="POST">
                            <div class="modal-header"><h5 class="modal-title">Edit Sale — <?= e($s['invoice_no']) ?></h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                            <div class="modal-body row g-3">
                                <div class="col-md-6"><label class="form-label">Customer Name</label><input type="text" name="customer_name" class="form-control" value="<?= e($s['customer_name']) ?>" required></div>
                                <div class="col-md-6"><label class="form-label">Contact</label><input type="text" name="customer_contact" class="form-control" value="<?= e($s['customer_contact']) ?>"></div>
                                <div class="col-md-6"><label class="form-label">Product</label><input type="text" name="product_name" class="form-control" value="<?= e($s['product_name']) ?>" required></div>
                                <div class="col-md-3"><label class="form-label">Quantity</label><input type="number" step="0.01" name="quantity" class="form-control" value="<?= e($s['quantity']) ?>" required></div>
                                <div class="col-md-3"><label class="form-label">Unit</label><input type="text" name="unit" class="form-control" value="<?= e($s['unit']) ?>"></div>
                                <div class="col-md-4"><label class="form-label">Unit Price (₱)</label><input type="number" step="0.01" name="unit_price" class="form-control" value="<?= e($s['unit_price']) ?>" required></div>
                                <div class="col-md-4"><label class="form-label">Sale Date</label><input type="date" name="sale_date" class="form-control" value="<?= e($s['sale_date']) ?>" required></div>
                                <div class="col-md-4">
                                    <label class="form-label">Payment Status</label>
                                    <select name="payment_status" class="form-select">
                                        <?php foreach (['Paid','Pending','Partial'] as $p): ?>
                                            <option value="<?= $p ?>" <?= $p==$s['payment_status']?'selected':'' ?>><?= $p ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Sold By</label>
                                    <select name="sold_by" class="form-select">
                                        <option value="">— None —</option>
                                        <?php foreach ($employees as $emp): ?>
                                            <option value="<?= $emp['employee_id'] ?>" <?= $emp['employee_id']==$s['sold_by']?'selected':'' ?>><?= e($emp['first_name'].' '.$emp['last_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
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
    <div class="modal-dialog modal-lg">
        <form class="modal-content" action="<?= BASE_URL ?>/sales/store" method="POST">
            <div class="modal-header"><h5 class="modal-title">New Sale</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body row g-3">
                <div class="col-md-6"><label class="form-label">Invoice No.</label><input type="text" name="invoice_no" class="form-control" value="<?= e($nextInvoice) ?>" required></div>
                <div class="col-md-6"><label class="form-label">Sale Date</label><input type="date" name="sale_date" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
                <div class="col-md-6"><label class="form-label">Customer Name</label><input type="text" name="customer_name" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Contact</label><input type="text" name="customer_contact" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Product</label><input type="text" name="product_name" class="form-control" placeholder="e.g. Fresh Oyster Mushroom" required></div>
                <div class="col-md-3"><label class="form-label">Quantity</label><input type="number" step="0.01" name="quantity" class="form-control" required></div>
                <div class="col-md-3"><label class="form-label">Unit</label><input type="text" name="unit" class="form-control" value="kg"></div>
                <div class="col-md-4"><label class="form-label">Unit Price (₱)</label><input type="number" step="0.01" name="unit_price" class="form-control" required></div>
                <div class="col-md-4">
                    <label class="form-label">Payment Status</label>
                    <select name="payment_status" class="form-select"><option>Paid</option><option>Pending</option><option>Partial</option></select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sold By</label>
                    <select name="sold_by" class="form-select">
                        <option value="">— None —</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['employee_id'] ?>"><?= e($emp['first_name'].' '.$emp['last_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-success">Save Sale</button></div>
        </form>
    </div>
</div>
