<?php
class Inventory extends Model
{
    protected string $table = 'inventory';
    protected string $primaryKey = 'inventory_id';

    public function all(string $orderBy = 'item_name ASC'): array
    {
        return parent::all($orderBy);
    }

    public static function deriveStatus(float $quantity, float $reorderLevel): string
    {
        if ($quantity <= 0) return 'Out of Stock';
        if ($quantity <= $reorderLevel) return 'Low Stock';
        return 'In Stock';
    }
}
