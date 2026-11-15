<?php
class Dashboard extends Model
{
    public function activeBatches(): int
    {
        return (int) $this->scalar("SELECT COUNT(*) FROM production WHERE status IN ('Incubation','Fruiting','Harvesting')");
    }

    public function totalHarvestThisMonth(): float
    {
        return (float) $this->scalar(
            "SELECT COALESCE(SUM(weight_kg),0) FROM harvest WHERE MONTH(harvest_date) = MONTH(CURDATE()) AND YEAR(harvest_date) = YEAR(CURDATE())"
        );
    }

    public function salesThisMonth(): float
    {
        return (float) $this->scalar(
            "SELECT COALESCE(SUM(total_amount),0) FROM sales WHERE MONTH(sale_date) = MONTH(CURDATE()) AND YEAR(sale_date) = YEAR(CURDATE())"
        );
    }

    public function expensesThisMonth(): float
    {
        return (float) $this->scalar(
            "SELECT COALESCE(SUM(amount),0) FROM expenses WHERE MONTH(expense_date) = MONTH(CURDATE()) AND YEAR(expense_date) = YEAR(CURDATE())"
        );
    }

    public function lowStockCount(): int
    {
        return (int) $this->scalar("SELECT COUNT(*) FROM inventory WHERE status IN ('Low Stock','Out of Stock')");
    }

    public function pendingDeliveries(): int
    {
        return (int) $this->scalar("SELECT COUNT(*) FROM delivery WHERE delivery_status IN ('Pending','In Transit')");
    }

    public function activeEmployees(): int
    {
        return (int) $this->scalar("SELECT COUNT(*) FROM employees WHERE status = 'active'");
    }

    public function recentSales(int $limit = 5): array
    {
        return $this->query("SELECT * FROM sales ORDER BY sale_date DESC, sale_id DESC LIMIT {$limit}");
    }

    public function recentHarvests(int $limit = 5): array
    {
        return $this->query(
            "SELECT h.*, p.batch_code, p.mushroom_variety FROM harvest h
             JOIN production p ON p.production_id = h.production_id
             ORDER BY h.harvest_date DESC, h.harvest_id DESC LIMIT {$limit}"
        );
    }

    public function lowStockItems(int $limit = 5): array
    {
        return $this->query(
            "SELECT * FROM inventory WHERE status IN ('Low Stock','Out of Stock') ORDER BY quantity ASC LIMIT {$limit}"
        );
    }

    public function salesLast6Months(): array
    {
        return $this->query(
            "SELECT DATE_FORMAT(sale_date, '%Y-%m') AS ym, DATE_FORMAT(sale_date, '%b %Y') AS label, SUM(total_amount) AS total
             FROM sales
             WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
             GROUP BY ym, label ORDER BY ym ASC"
        );
    }

    public function harvestByVariety(): array
    {
        return $this->query(
            "SELECT p.mushroom_variety, COALESCE(SUM(h.weight_kg),0) AS total_kg
             FROM production p
             LEFT JOIN harvest h ON h.production_id = p.production_id
             GROUP BY p.mushroom_variety
             ORDER BY total_kg DESC"
        );
    }

    public function productionOverview(): array
    {
        return $this->query(
            "SELECT status, COUNT(*) AS batch_count
             FROM production
             GROUP BY status
             ORDER BY FIELD(status, 'Incubation', 'Fruiting', 'Harvesting', 'Completed', 'Failed')"
        );
    }

    public function upcomingHarvests(int $limit = 5): array
    {
        $limit = max(1, min(10, $limit));
        return $this->query(
            "SELECT production_id, batch_code, mushroom_variety, expected_harvest_date, status
             FROM production
             WHERE expected_harvest_date >= CURDATE()
               AND status IN ('Incubation', 'Fruiting', 'Harvesting')
             ORDER BY expected_harvest_date ASC, production_id ASC
             LIMIT {$limit}"
        );
    }

    public function recentActivities(bool $includeSales, int $limit = 6): array
    {
        $limit = max(1, min(12, $limit));
        $salesActivity = $includeSales
            ? " UNION ALL
                SELECT s.sale_date AS activity_date, 'Sale recorded' AS activity_type,
                       CONCAT(s.invoice_no, ' · ', s.customer_name) AS activity_detail,
                       s.total_amount AS activity_value, 'PHP' AS activity_unit
                FROM sales s"
            : '';

        return $this->query(
            "SELECT activity_date, activity_type, activity_detail, activity_value, activity_unit
             FROM (
                SELECT h.harvest_date AS activity_date, 'Harvest recorded' AS activity_type,
                       CONCAT(p.batch_code, ' · ', p.mushroom_variety) AS activity_detail,
                       h.weight_kg AS activity_value, 'kg' AS activity_unit
                FROM harvest h
                JOIN production p ON p.production_id = h.production_id
                UNION ALL
                SELECT pr.process_date AS activity_date, CONCAT(pr.process_type, ' processing') AS activity_type,
                       CONCAT(p.batch_code, ' · ', p.mushroom_variety) AS activity_detail,
                       pr.output_weight_kg AS activity_value, pr.output_unit AS activity_unit
                FROM processing pr
                JOIN harvest h ON h.harvest_id = pr.harvest_id
                JOIN production p ON p.production_id = h.production_id
                {$salesActivity}
             ) AS farm_activity
             ORDER BY activity_date DESC
             LIMIT {$limit}"
        );
    }
}
