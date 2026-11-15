<?php
class Harvest extends Model
{
    protected string $table = 'harvest';
    protected string $primaryKey = 'harvest_id';

    public function allWithDetails(): array
    {
        return $this->query(
            "SELECT h.*, p.batch_code, p.mushroom_variety, CONCAT(e.first_name,' ',e.last_name) AS harvester_name
             FROM harvest h
             JOIN production p ON p.production_id = h.production_id
             LEFT JOIN employees e ON e.employee_id = h.harvested_by
             ORDER BY h.harvest_date DESC, h.harvest_id DESC"
        );
    }

    public function totalForBatch(int $productionId): float
    {
        return (float) $this->scalar("SELECT COALESCE(SUM(weight_kg),0) FROM harvest WHERE production_id = :p", ['p' => $productionId]);
    }

    public function forDropdown(): array
    {
        return $this->query(
            "SELECT h.harvest_id, h.harvest_date, h.weight_kg, p.batch_code, p.mushroom_variety
             FROM harvest h JOIN production p ON p.production_id = h.production_id
             ORDER BY h.harvest_date DESC"
        );
    }
}
