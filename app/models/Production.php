<?php
class Production extends Model
{
    protected string $table = 'production';
    protected string $primaryKey = 'production_id';

    public function allWithEmployee(): array
    {
        return $this->query(
            "SELECT p.*, CONCAT(e.first_name, ' ', e.last_name) AS employee_name
             FROM production p
             LEFT JOIN employees e ON e.employee_id = p.responsible_employee_id
             ORDER BY p.inoculation_date DESC, p.production_id DESC"
        );
    }

    public function generateBatchCode(): string
    {
        $year = date('Y');
        $count = (int) $this->scalar("SELECT COUNT(*) FROM production WHERE batch_code LIKE :p", ['p' => "BATCH-{$year}-%"]);
        return sprintf('BATCH-%s-%03d', $year, $count + 1);
    }

    public function activeForDropdown(): array
    {
        return $this->query(
            "SELECT production_id, batch_code, mushroom_variety, status FROM production
             WHERE status IN ('Fruiting','Harvesting') ORDER BY batch_code"
        );
    }
}
