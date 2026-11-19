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
        $maxSequence = (int) $this->scalar(
            "SELECT COALESCE(MAX(CAST(SUBSTRING_INDEX(batch_code, '-', -1) AS UNSIGNED)), 0)
             FROM production
             WHERE batch_code LIKE :p",
            ['p' => "BATCH-{$year}-%"]
        );
        return sprintf('BATCH-%s-%03d', $year, $maxSequence + 1);
    }

    public function insertWithGeneratedBatchCode(array $data): bool
    {
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $data['batch_code'] = $this->generateBatchCode();

            try {
                return $this->insert($data);
            } catch (PDOException $e) {
                $isDuplicateKey = $e->getCode() === '23000'
                    && (int) ($e->errorInfo[1] ?? 0) === 1062;

                if (!$isDuplicateKey || $attempt === 2) {
                    throw $e;
                }
            }
        }

        return false;
    }

    public function activeForDropdown(): array
    {
        return $this->query(
            "SELECT production_id, batch_code, mushroom_variety, status FROM production
             WHERE status IN ('Fruiting','Harvesting') ORDER BY batch_code"
        );
    }

    public function allForDropdown(): array
    {
        return $this->query(
            "SELECT production_id, batch_code, mushroom_variety, status FROM production
             ORDER BY inoculation_date DESC, batch_code DESC"
        );
    }
}
