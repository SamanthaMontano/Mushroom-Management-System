<?php
class Sale extends Model
{
    protected string $table = 'sales';
    protected string $primaryKey = 'sale_id';

    public function allWithSeller(): array
    {
        return $this->query(
            "SELECT s.*, CONCAT(e.first_name,' ',e.last_name) AS seller_name
             FROM sales s LEFT JOIN employees e ON e.employee_id = s.sold_by
             ORDER BY s.sale_date DESC, s.sale_id DESC"
        );
    }

    public function receiptById(int $id)
    {
        return $this->queryOne(
            "SELECT s.*, CONCAT(e.first_name,' ',e.last_name) AS seller_name
             FROM sales s LEFT JOIN employees e ON e.employee_id = s.sold_by
             WHERE s.sale_id = :sale_id
             LIMIT 1",
            ['sale_id' => $id]
        );
    }

    public function generateInvoiceNo(): string
    {
        $year = date('Y');
        $count = (int) $this->scalar("SELECT COUNT(*) FROM sales WHERE invoice_no LIKE :p", ['p' => "INV-{$year}-%"]);
        return sprintf('INV-%s-%04d', $year, $count + 1);
    }

    public function forDropdown(): array
    {
        return $this->query("SELECT sale_id, invoice_no, customer_name, product_name FROM sales ORDER BY sale_date DESC");
    }
}
