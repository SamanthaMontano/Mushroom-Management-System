<?php
class Report extends Model
{
    public function salesTotal(string $from, string $to): float
    {
        return (float) $this->scalar(
            "SELECT COALESCE(SUM(total_amount),0) FROM sales WHERE sale_date BETWEEN :f AND :t",
            ['f' => $from, 't' => $to]
        );
    }

    public function expenseTotal(string $from, string $to): float
    {
        return (float) $this->scalar(
            "SELECT COALESCE(SUM(amount),0) FROM expenses WHERE expense_date BETWEEN :f AND :t",
            ['f' => $from, 't' => $to]
        );
    }

    public function harvestTotal(string $from, string $to): float
    {
        return (float) $this->scalar(
            "SELECT COALESCE(SUM(weight_kg),0) FROM harvest WHERE harvest_date BETWEEN :f AND :t",
            ['f' => $from, 't' => $to]
        );
    }

    public function salesByProduct(string $from, string $to): array
    {
        return $this->query(
            "SELECT product_name, SUM(quantity) AS qty, SUM(total_amount) AS total
             FROM sales WHERE sale_date BETWEEN :f AND :t
             GROUP BY product_name ORDER BY total DESC",
            ['f' => $from, 't' => $to]
        );
    }

    public function expensesByCategory(string $from, string $to): array
    {
        return $this->query(
            "SELECT category, SUM(amount) AS total
             FROM expenses WHERE expense_date BETWEEN :f AND :t
             GROUP BY category ORDER BY total DESC",
            ['f' => $from, 't' => $to]
        );
    }

    public function productionSummary(string $from, string $to): array
    {
        return $this->query(
            "SELECT p.batch_code, p.mushroom_variety, p.status, p.bag_quantity,
                    COALESCE(SUM(h.weight_kg),0) AS total_harvest_kg
             FROM production p
             LEFT JOIN harvest h ON h.production_id = p.production_id AND h.harvest_date BETWEEN :f AND :t
             WHERE p.inoculation_date BETWEEN :f2 AND :t2 OR EXISTS (
                SELECT 1 FROM harvest h2 WHERE h2.production_id = p.production_id AND h2.harvest_date BETWEEN :f3 AND :t3
             )
             GROUP BY p.production_id
             ORDER BY p.inoculation_date DESC",
            ['f' => $from, 't' => $to, 'f2' => $from, 't2' => $to, 'f3' => $from, 't3' => $to]
        );
    }
}
