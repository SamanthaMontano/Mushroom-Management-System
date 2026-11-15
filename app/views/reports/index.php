<div class="page-header">
    <div>
        <h3>Reports</h3>
        <p>Production, sales, and expense summary for a selected period.</p>
    </div>
    <button class="btn btn-outline-success" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
</div>

<div class="card-panel mb-3">
    <form method="GET" action="<?= BASE_URL ?>/report" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label">From</label>
            <input type="date" name="from" class="form-control" value="<?= e($from) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">To</label>
            <input type="date" name="to" class="form-control" value="<?= e($to) ?>">
        </div>
        <div class="col-md-4">
            <button class="btn btn-success"><i class="bi bi-funnel"></i> Apply Filter</button>
        </div>
    </form>
</div>

<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon--blue"><i class="bi bi-cash-coin"></i></div>
            <div class="stat-value"><?= peso($salesTotal) ?></div>
            <div class="stat-label">Total Sales</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon--red"><i class="bi bi-receipt"></i></div>
            <div class="stat-value"><?= peso($expenseTotal) ?></div>
            <div class="stat-label">Total Expenses</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon <?= $netProfit >= 0 ? 'stat-icon--green' : 'stat-icon--red' ?>"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="stat-value"><?= peso($netProfit) ?></div>
            <div class="stat-label">Net Profit / Loss</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon--amber"><i class="bi bi-basket-fill"></i></div>
            <div class="stat-value"><?= number_format($harvestTotal,1) ?> kg</div>
            <div class="stat-label">Total Harvest</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-6">
        <div class="card-panel h-100">
            <h6 class="fw-semibold mb-3">Sales by Product</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>Product</th><th>Qty</th><th>Total</th></tr></thead>
                    <tbody>
                    <?php if (empty($salesByProduct)): ?><tr><td colspan="3" class="text-muted text-center py-3">No sales in this period.</td></tr><?php endif; ?>
                    <?php foreach ($salesByProduct as $row): ?>
                        <tr><td><?= e($row['product_name']) ?></td><td><?= number_format($row['qty'],2) ?></td><td class="fw-semibold"><?= peso($row['total']) ?></td></tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-panel h-100">
            <h6 class="fw-semibold mb-3">Expenses by Category</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>Category</th><th>Total</th></tr></thead>
                    <tbody>
                    <?php if (empty($expensesByCategory)): ?><tr><td colspan="2" class="text-muted text-center py-3">No expenses in this period.</td></tr><?php endif; ?>
                    <?php foreach ($expensesByCategory as $row): ?>
                        <tr><td><?= e($row['category']) ?></td><td class="fw-semibold text-danger"><?= peso($row['total']) ?></td></tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card-panel">
    <h6 class="fw-semibold mb-3">Production Summary</h6>
    <div class="table-responsive">
        <table class="table table-sm">
            <thead><tr><th>Batch</th><th>Variety</th><th>Bags</th><th>Status</th><th>Harvest in Period (kg)</th></tr></thead>
            <tbody>
            <?php if (empty($productionSummary)): ?><tr><td colspan="5" class="text-muted text-center py-3">No production activity in this period.</td></tr><?php endif; ?>
            <?php foreach ($productionSummary as $row): ?>
                <tr>
                    <td class="fw-semibold"><?= e($row['batch_code']) ?></td>
                    <td><?= e($row['mushroom_variety']) ?></td>
                    <td><?= (int)$row['bag_quantity'] ?></td>
                    <td><?= statusBadge($row['status']) ?></td>
                    <td><?= number_format($row['total_harvest_kg'],2) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
