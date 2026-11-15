<?php
class Processing extends Model
{
    protected string $table = 'processing';
    protected string $primaryKey = 'processing_id';

    public function allWithDetails(): array
    {
        return $this->query(
            "SELECT pr.*, p.batch_code, p.mushroom_variety, CONCAT(e.first_name,' ',e.last_name) AS processor_name
             FROM processing pr
             JOIN harvest h ON h.harvest_id = pr.harvest_id
             JOIN production p ON p.production_id = h.production_id
             LEFT JOIN employees e ON e.employee_id = pr.processed_by
             ORDER BY pr.process_date DESC, pr.processing_id DESC"
        );
    }
}
