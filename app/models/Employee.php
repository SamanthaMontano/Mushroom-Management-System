<?php
class Employee extends Model
{
    protected string $table = 'employees';
    protected string $primaryKey = 'employee_id';

    public function findByUsername(string $username)
    {
        return $this->queryOne("SELECT * FROM employees WHERE username = :u LIMIT 1", ['u' => $username]);
    }

    public function attemptLogin(string $username, string $password)
    {
        $employee = $this->findByUsername($username);
        if ($employee && password_verify($password, $employee['password'])) {
            return $employee;
        }
        return false;
    }

    public function activeEmployees(): array
    {
        return $this->query("SELECT * FROM employees WHERE status = 'active' ORDER BY first_name");
    }

    public function updatePassword($id, string $plainPassword): bool
    {
        return $this->update($id, ['password' => password_hash($plainPassword, PASSWORD_BCRYPT)]);
    }

    public function countByStatus(string $status): int
    {
        return (int) $this->scalar("SELECT COUNT(*) FROM employees WHERE status = :s", ['s' => $status]);
    }
}
