<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $sale ? 'Receipt ' . e($sale['invoice_no']) : 'Receipt unavailable' ?> - <?= e(APP_NAME) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body class="receipt-body">
    <main class="receipt-page">
        <?php if (!$sale): ?>
            <section class="receipt-unavailable">
                <h1>Receipt unavailable</h1>
                <p>This sale could not be found. Return to Sales and try again.</p>
                <a class="receipt-button receipt-button-secondary" href="<?= BASE_URL ?>/sales">Back to Sales</a>
            </section>
        <?php else: ?>
            <div class="receipt-toolbar">
                <a class="receipt-button receipt-button-secondary" href="<?= BASE_URL ?>/sales"><i class="bi bi-arrow-left"></i> Back to Sales</a>
                <button class="receipt-button receipt-button-primary" type="button" onclick="window.print()"><i class="bi bi-printer"></i> Print Receipt</button>
            </div>

            <article class="receipt-paper" aria-label="Sales receipt">
                <header class="receipt-header">
                    <img src="<?= BASE_URL ?>/images/cmd-logo.png" alt="CDM Mushroom Farm" class="receipt-logo">
                    <p class="receipt-business-name">CDM Mushroom Farm</p>
                    <p class="receipt-document-title">Sales Receipt</p>
                </header>

                <dl class="receipt-meta">
                    <div><dt>Receipt No.</dt><dd><?= e($sale['invoice_no']) ?></dd></div>
                    <div><dt>Date</dt><dd><?= niceDate($sale['sale_date']) ?></dd></div>
                    <div><dt>Customer</dt><dd><?= e($sale['customer_name']) ?></dd></div>
                    <?php if (!empty($sale['customer_contact'])): ?>
                        <div><dt>Contact</dt><dd><?= e($sale['customer_contact']) ?></dd></div>
                    <?php endif; ?>
                </dl>

                <div class="receipt-items-wrap">
                    <table class="receipt-items">
                        <thead>
                            <tr><th>Item</th><th>Qty</th><th>Price</th><th class="receipt-number">Amount</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= e($sale['product_name']) ?></td>
                                <td><?= number_format((float) $sale['quantity'], 2) ?> <?= e($sale['unit']) ?></td>
                                <td><?= peso($sale['unit_price']) ?></td>
                                <td class="receipt-number"><?= peso($sale['total_amount']) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <dl class="receipt-totals">
                    <div><dt>Total</dt><dd><?= peso($sale['total_amount']) ?></dd></div>
                    <div><dt>Payment status</dt><dd><?= e($sale['payment_status']) ?></dd></div>
                </dl>

                <footer class="receipt-footer">
                    <?php if (!empty($sale['seller_name'])): ?>
                        <p>Served by <?= e($sale['seller_name']) ?></p>
                    <?php endif; ?>
                    <p>Thank you for supporting our farm.</p>
                </footer>
            </article>
        <?php endif; ?>
    </main>
</body>
</html>
