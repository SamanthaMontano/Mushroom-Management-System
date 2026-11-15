<?php
class Expense extends Model
{
    protected string $table = 'expenses';
    protected string $primaryKey = 'expense_id';

    public function allWithEmployee(): array
    {
        return $this->query(
            "SELECT ex.*, CONCAT(e.first_name,' ',e.last_name) AS recorder_name
             FROM expenses ex LEFT JOIN employees e ON e.employee_id = ex.recorded_by
             ORDER BY ex.expense_date DESC, ex.expense_id DESC"
        );
    }

    public function totalThisMonth(): float
    {
        return (float) $this->scalar(
            "SELECT COALESCE(SUM(amount),0) FROM expenses WHERE MONTH(expense_date)=MONTH(CURDATE()) AND YEAR(expense_date)=YEAR(CURDATE())"
        );
    }

    public function byCategory(): array
    {
        return $this->query("SELECT category, SUM(amount) AS total FROM expenses GROUP BY category ORDER BY total DESC");
    }
}
