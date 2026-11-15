<?php
class EmployeeController extends Controller
{
    public function index()
    {
        $this->requireOwner();
        $data = [
            'pageTitle' => 'Employees',
            'employees' => $this->model('Employee')->all('status ASC, first_name ASC'),
        ];
        $this->view('employees/index', $data);
    }

    public function store()
    {
        $this->requireOwner();
        if ($this->isPost()) {
            $password = $this->input('password', '');
            if (strlen($password) < 8) {
                $this->setFlash('danger', 'Set an initial password with at least 8 characters.');
                $this->redirect('employee');
            }
            $this->model('Employee')->insert([
                'first_name'  => $this->input('first_name'),
                'last_name'   => $this->input('last_name'),
                'username'    => $this->input('username'),
                'password'    => password_hash($password, PASSWORD_BCRYPT),
                'email'       => $this->input('email'),
                'phone'       => $this->input('phone'),
                'position'    => $this->input('position'),
                'role'        => $this->input('role', 'staff'),
                'date_hired'  => $this->input('date_hired') ?: null,
                'salary'      => (float) $this->input('salary', 0),
                'status'      => $this->input('status', 'active'),
            ]);
            $this->setFlash('success', 'Employee added successfully.');
        }
        $this->redirect('employee');
    }

    public function update($id)
    {
        $this->requireOwner();
        if ($this->isPost()) {
            $data = [
                'first_name' => $this->input('first_name'),
                'last_name'  => $this->input('last_name'),
                'email'      => $this->input('email'),
                'phone'      => $this->input('phone'),
                'position'   => $this->input('position'),
                'role'       => $this->input('role'),
                'date_hired' => $this->input('date_hired') ?: null,
                'salary'     => (float) $this->input('salary', 0),
                'status'     => $this->input('status'),
            ];
            $newPassword = $this->input('password');
            if (!empty($newPassword)) {
                if (strlen($newPassword) < 8) {
                    $this->setFlash('danger', 'A new password must be at least 8 characters.');
                    $this->redirect('employee');
                }
                $data['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
            }
            $this->model('Employee')->update($id, $data);
            $this->setFlash('success', 'Employee updated.');
        }
        $this->redirect('employee');
    }

    public function delete($id)
    {
        $this->requireOwner();
        if ($this->isPost()) {
            if ((int)$id === (int)($_SESSION['employee_id'] ?? 0)) {
                $this->setFlash('danger', 'You cannot delete your own account.');
            } else {
                $this->model('Employee')->delete($id);
                $this->setFlash('success', 'Employee deleted.');
            }
        }
        $this->redirect('employee');
    }
}
