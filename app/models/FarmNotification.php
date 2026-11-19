<?php
class FarmNotification extends Model
{
    public function latest(): array
    {
        return $this->query(
            "SELECT CONCAT('stock-', inventory_id, '-', status, '-', updated_at) AS notification_id,
                    'inventory' AS type,
                    item_name AS title,
                    CONCAT(status, ' · ', quantity, ' ', unit, ' remaining') AS detail,
                    updated_at AS occurred_at
             FROM inventory
             WHERE status IN ('Low Stock', 'Out of Stock')
             UNION ALL
             SELECT CONCAT('harvest-', production_id, '-', expected_harvest_date, '-', status, '-', updated_at) AS notification_id,
                    'harvest' AS type,
                    CONCAT('Upcoming harvest · ', batch_code) AS title,
                    CONCAT(mushroom_variety, ' · ', status) AS detail,
                    expected_harvest_date AS occurred_at
             FROM production
             WHERE expected_harvest_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
               AND status IN ('Incubation', 'Fruiting', 'Harvesting')
             UNION ALL
             SELECT CONCAT('delivery-', delivery_id, '-', delivery_status, '-', updated_at) AS notification_id,
                    'delivery' AS type,
                    CONCAT('Delivery ', delivery_status) AS title,
                    CONCAT(invoice_no, ' · ', customer_name) AS detail,
                    d.updated_at AS occurred_at
             FROM delivery d
             JOIN sales s ON s.sale_id = d.sale_id
             WHERE d.delivery_status IN ('Pending', 'In Transit')
             ORDER BY occurred_at DESC
             LIMIT 30"
        );
    }
}
