<?php
$firstName = explode(' ', $_SESSION['employee_name'] ?? 'User')[0];
$harvestTotalAllTime = array_sum(array_map('floatval', array_column($harvestByVariety, 'total_kg')));
$productionTotal = array_sum(array_map('intval', array_column($productionOverview, 'batch_count')));
$harvestRows = array_values(array_filter($harvestByVariety, static function ($row) {
    return (float) $row['total_kg'] > 0;
}));
$harvestColors = ['#426B50', '#BD8658', '#78947B', '#A9BDA7', '#6E7D73', '#C7A17E'];
?>

<section class="dashboard-welcome">
    <img class="dashboard-welcome-image" src="<?= BASE_URL ?>/images/mushroom-farm.jpg" alt="" aria-hidden="true">
    <div class="dashboard-welcome-content">
        <span class="eyebrow">Farm operations overview</span>
        <h2>Welcome back, <?= e($firstName) ?></h2>
        <p>Your cultivation and harvest activity at a glance.</p>
        <span class="welcome-date"><i class="bi bi-calendar3" aria-hidden="true"></i> <?= date('l, F j, Y') ?></span>
    </div>
</section>

<div class="row g-3 mb-3">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon--green"><i class="bi bi-flower1"></i></div>
            <div class="stat-value"><?= (int) $activeBatches ?></div>
            <div class="stat-label">Active Batches</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon--amber"><i class="bi bi-basket2"></i></div>
            <div class="stat-value"><?= number_format((float) $totalHarvest, 1) ?> <small>kg</small></div>
            <div class="stat-label">Harvest This Month</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon--yellow"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="stat-value"><?= (int) $lowStockCount ?></div>
            <div class="stat-label">Low Stock Items</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon stat-icon--indigo"><i class="bi bi-truck"></i></div>
            <div class="stat-value"><?= (int) $pendingDeliveries ?></div>
            <div class="stat-label">Pending Deliveries</div>
        </div>
    </div>
</div>

<?php if ($isOwner): ?>
    <div class="owner-metrics mb-3" aria-label="Owner business summary">
        <div class="owner-metric"><span>Sales this month</span><strong><?= peso($salesThisMonth) ?></strong></div>
        <div class="owner-metric"><span>Expenses this month</span><strong><?= peso($expensesThisMonth) ?></strong></div>
        <div class="owner-metric"><span>Active employees</span><strong><?= (int) $activeEmployees ?></strong></div>
    </div>
<?php endif; ?>

<div class="row g-3 mb-3">
    <?php if ($isOwner): ?>
        <div class="col-lg-7">
            <section class="card-panel h-100" aria-labelledby="sales-trend-heading">
                <div class="dashboard-section-heading">
                    <h6 id="sales-trend-heading">Sales trend</h6>
                    <span class="section-note">Last 6 months</span>
                </div>
                <div class="chart-container"><canvas id="salesTrendChart" aria-label="Sales trend over the last six months"></canvas></div>
            </section>
        </div>
        <div class="col-lg-5">
    <?php else: ?>
        <div class="col-lg-12">
    <?php endif; ?>
        <section class="card-panel h-100" aria-labelledby="harvest-variety-heading">
            <div class="dashboard-section-heading">
                <h6 id="harvest-variety-heading">Harvest by variety</h6>
                <span class="section-note">All-time yield</span>
            </div>
            <?php if ($harvestTotalAllTime > 0): ?>
                <div class="harvest-chart-layout">
                    <div class="harvest-donut-wrap">
                        <canvas id="harvestVarietyChart" aria-label="Harvest weight by mushroom variety"></canvas>
                        <div class="harvest-donut-center" aria-hidden="true">
                            <strong><?= number_format($harvestTotalAllTime, 1) ?></strong>
                            <span>Total kg</span>
                        </div>
                    </div>
                    <div class="harvest-legend" aria-label="Harvest by variety values and percentages">
                        <?php foreach ($harvestRows as $index => $row): ?>
                            <?php $percentage = $harvestTotalAllTime > 0 ? ((float) $row['total_kg'] / $harvestTotalAllTime) * 100 : 0; ?>
                            <div class="harvest-legend-row">
                                <div class="harvest-legend-name">
                                    <span class="harvest-legend-dot" style="background:<?= e($harvestColors[$index % count($harvestColors)]) ?>"></span>
                                    <span><?= e($row['mushroom_variety']) ?></span>
                                </div>
                                <div class="harvest-legend-value">
                                    <?= number_format((float) $row['total_kg'], 1) ?> kg
                                    <small><?= number_format($percentage, 0) ?>%</small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="dashboard-empty">Harvest data will appear here once a harvest is recorded.</div>
            <?php endif; ?>
        </section>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-5">
        <section class="card-panel h-100" aria-labelledby="production-overview-heading">
            <div class="dashboard-section-heading">
                <h6 id="production-overview-heading">Production overview</h6>
                <span class="section-note"><?= $productionTotal ?> total batches</span>
            </div>
            <?php if ($productionTotal > 0): ?>
                <div class="production-overview-list">
                    <?php foreach ($productionOverview as $row): ?>
                        <?php $batchCount = (int) $row['batch_count']; ?>
                        <div class="production-overview-row">
                            <span class="status-name"><?= e($row['status']) ?></span>
                            <span class="production-progress" aria-label="<?= e($row['status']) ?>: <?= $batchCount ?> batches">
                                <span class="status-<?= e(strtolower($row['status'])) ?>" style="width:<?= number_format(($batchCount / $productionTotal) * 100, 2, '.', '') ?>%"></span>
                            </span>
                            <span class="status-count"><?= $batchCount ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="dashboard-empty">Production batches will appear here when created.</div>
            <?php endif; ?>
        </section>
    </div>
    <div class="col-lg-7">
        <section class="card-panel h-100" aria-labelledby="upcoming-harvests-heading">
            <div class="dashboard-section-heading">
                <h6 id="upcoming-harvests-heading">Upcoming harvests</h6>
                <a class="section-note text-decoration-none" href="<?= BASE_URL ?>/production">View production <i class="bi bi-arrow-up-right"></i></a>
            </div>
            <?php if (!empty($upcomingHarvests)): ?>
                <div class="upcoming-list">
                    <?php foreach ($upcomingHarvests as $batch): ?>
                        <div class="upcoming-row">
                            <span class="activity-mark"><i class="bi bi-calendar-event" aria-hidden="true"></i></span>
                            <div class="upcoming-copy">
                                <div class="upcoming-title"><?= e($batch['batch_code']) ?> <span class="text-muted fw-normal">· <?= e($batch['mushroom_variety']) ?></span></div>
                                <div class="upcoming-detail"><?= e($batch['status']) ?></div>
                            </div>
                            <span class="upcoming-date"><?= niceDate($batch['expected_harvest_date'], 'M j, Y') ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="dashboard-empty">No upcoming harvest dates are scheduled.</div>
            <?php endif; ?>
        </section>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-4">
        <section class="card-panel h-100" aria-labelledby="recent-harvests-heading">
            <div class="dashboard-section-heading">
                <h6 id="recent-harvests-heading">Recent harvests</h6>
                <a class="section-note text-decoration-none" href="<?= BASE_URL ?>/harvest">View all <i class="bi bi-arrow-up-right"></i></a>
            </div>
            <?php if (!empty($recentHarvests)): ?>
                <div class="activity-list">
                    <?php foreach ($recentHarvests as $harvest): ?>
                        <div class="activity-row">
                            <span class="activity-mark"><i class="bi bi-basket2" aria-hidden="true"></i></span>
                            <div class="activity-copy">
                                <div class="activity-title"><?= e($harvest['batch_code']) ?> · <?= e($harvest['mushroom_variety']) ?></div>
                                <div class="activity-detail"><?= number_format((float) $harvest['weight_kg'], 2) ?> kg · Grade <?= e($harvest['grade']) ?></div>
                            </div>
                            <span class="activity-date"><?= niceDate($harvest['harvest_date'], 'M j') ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="dashboard-empty">No harvest records yet.</div>
            <?php endif; ?>
        </section>
    </div>
    <div class="col-lg-4">
        <section class="card-panel h-100" aria-labelledby="recent-activities-heading">
            <div class="dashboard-section-heading">
                <h6 id="recent-activities-heading">Recent activities</h6>
                <span class="section-note">Latest recorded activity</span>
            </div>
            <?php if (!empty($recentActivities)): ?>
                <div class="activity-list">
                    <?php foreach ($recentActivities as $activity): ?>
                        <div class="activity-row">
                            <span class="activity-mark">
                                <i class="bi <?= $activity['activity_type'] === 'Sale recorded' ? 'bi-receipt' : ($activity['activity_type'] === 'Harvest recorded' ? 'bi-basket2' : 'bi-gear') ?>" aria-hidden="true"></i>
                            </span>
                            <div class="activity-copy">
                                <div class="activity-title"><?= e($activity['activity_type']) ?></div>
                                <div class="activity-detail"><?= e($activity['activity_detail']) ?></div>
                            </div>
                            <div class="text-end">
                                <div class="activity-date"><?= niceDate($activity['activity_date'], 'M j') ?></div>
                                <div class="activity-detail">
                                    <?= $activity['activity_unit'] === 'PHP' ? peso($activity['activity_value']) : number_format((float) $activity['activity_value'], 1) . ' ' . e($activity['activity_unit']) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="dashboard-empty">Recorded harvest and processing activity will appear here.</div>
            <?php endif; ?>
        </section>
    </div>
    <div class="col-lg-4">
        <section class="card-panel h-100" aria-labelledby="low-stock-heading">
            <div class="dashboard-section-heading">
                <h6 id="low-stock-heading">Low stock alerts</h6>
                <a class="section-note text-decoration-none" href="<?= BASE_URL ?>/inventory">Inventory <i class="bi bi-arrow-up-right"></i></a>
            </div>
            <?php if (!empty($lowStockItems)): ?>
                <div class="activity-list">
                    <?php foreach ($lowStockItems as $item): ?>
                        <div class="activity-row">
                            <span class="activity-mark"><i class="bi bi-box-seam" aria-hidden="true"></i></span>
                            <div class="activity-copy">
                                <div class="activity-title"><?= e($item['item_name']) ?></div>
                                <div class="activity-detail"><?= number_format((float) $item['quantity'], 1) ?> <?= e($item['unit']) ?> remaining</div>
                            </div>
                            <span><?= statusBadge($item['status']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="dashboard-empty">No low-stock items at this time.</div>
            <?php endif; ?>
        </section>
    </div>
</div>

<section class="card-panel" aria-labelledby="quick-actions-heading">
    <div class="dashboard-section-heading">
        <h6 id="quick-actions-heading">Quick actions</h6>
        <span class="section-note">Go to a farm workflow</span>
    </div>
    <div class="dashboard-quick-actions">
        <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/production"><i class="bi bi-plus-lg"></i> New batch</a>
        <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/harvest"><i class="bi bi-basket2"></i> Record harvest</a>
        <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/processing"><i class="bi bi-gear"></i> Processing</a>
        <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/inventory"><i class="bi bi-box-seam"></i> Inventory</a>
        <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/sales"><i class="bi bi-receipt"></i> Sales</a>
        <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/delivery"><i class="bi bi-truck"></i> Delivery</a>
        <?php if ($isOwner): ?>
            <a class="btn btn-outline-success" href="<?= BASE_URL ?>/report"><i class="bi bi-bar-chart"></i> Reports</a>
        <?php endif; ?>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
const harvestLabels = <?= json_encode(array_column($harvestRows, 'mushroom_variety'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
const harvestValues = <?= json_encode(array_map('floatval', array_column($harvestRows, 'total_kg'))) ?>;
const harvestColors = <?= json_encode(array_slice(array_merge($harvestColors, $harvestColors), 0, count($harvestRows))) ?>;
const harvestCanvas = document.getElementById('harvestVarietyChart');
if (harvestCanvas) {
    new Chart(harvestCanvas, {
        type: 'doughnut',
        data: {
            labels: harvestLabels,
            datasets: [{
                data: harvestValues,
                backgroundColor: harvestColors,
                borderColor: '#ffffff',
                borderWidth: 3,
                hoverOffset: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '76%',
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: function (context) {
                const total = context.dataset.data.reduce((sum, value) => sum + value, 0);
                const value = Number(context.raw);
                const share = total > 0 ? (value / total * 100).toFixed(1) : '0.0';
                return ' ' + value.toFixed(1) + ' kg (' + share + '%)';
            } } } }
        }
    });
}

<?php if ($isOwner): ?>
const salesLabels = <?= json_encode(array_column($salesTrend, 'label'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
const salesData = <?= json_encode(array_map('floatval', array_column($salesTrend, 'total'))) ?>;
const salesCanvas = document.getElementById('salesTrendChart');
if (salesCanvas) {
    new Chart(salesCanvas, {
        type: 'line',
        data: {
            labels: salesLabels,
            datasets: [{
                label: 'Sales',
                data: salesData,
                borderColor: '#426B50',
                backgroundColor: 'rgba(66,107,80,.08)',
                fill: true,
                tension: .3,
                pointRadius: 2,
                pointHoverRadius: 4,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, border: { display: false } },
                y: { beginAtZero: true, border: { display: false }, grid: { color: '#edf0ed' }, ticks: { maxTicksLimit: 5 } }
            }
        }
    });
}
<?php endif; ?>
</script>
