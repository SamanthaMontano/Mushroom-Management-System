<?php
class Delivery extends Model
{
    protected string $table = 'delivery';
    protected string $primaryKey = 'delivery_id';

    public function allWithSale(): array
    {
        return $this->query(
            "SELECT d.*, s.invoice_no, s.customer_name, s.product_name
             FROM delivery d JOIN sales s ON s.sale_id = d.sale_id
             ORDER BY d.delivery_date DESC, d.delivery_id DESC"
        );
    }
}
