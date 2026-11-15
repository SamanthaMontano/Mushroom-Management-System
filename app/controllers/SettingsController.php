<?php
class SettingsController extends Controller
{
    public function index()
    {
        $this->view('settings/index', [
            'pageTitle' => 'Settings',
            'accountName' => $_SESSION['employee_name'] ?? '',
            'accountType' => ($_SESSION['role'] ?? '') === 'admin' ? 'Owner' : 'Employee',
            'position' => $_SESSION['position'] ?? '',
        ]);
    }

    public function changePassword()
    {
        if (!$this->isPost()) {
            $this->redirect('settings');
        }

        if (!verifyCsrf()) {
            $this->setFlash('danger', 'Your session expired. Please try again.');
            $this->redirect('settings');
        }

        $currentPassword = isset($_POST['current_password']) && is_string($_POST['current_password'])
            ? $_POST['current_password']
            : '';
        $newPassword = isset($_POST['new_password']) && is_string($_POST['new_password'])
            ? $_POST['new_password']
            : '';
        $confirmPassword = isset($_POST['confirm_password']) && is_string($_POST['confirm_password'])
            ? $_POST['confirm_password']
            : '';

        $employeeModel = $this->model('Employee');
        $employee = $employeeModel->find($_SESSION['employee_id']);

        if (!$employee || !password_verify($currentPassword, $employee['password'])) {
            $this->setFlash('danger', 'Current password is incorrect.');
            $this->redirect('settings');
        }
        if (strlen($newPassword) < 8) {
            $this->setFlash('danger', 'New password must be at least 8 characters.');
            $this->redirect('settings');
        }
        if ($newPassword !== $confirmPassword) {
            $this->setFlash('danger', 'New password and confirmation do not match.');
            $this->redirect('settings');
        }
        if (!$employeeModel->updatePassword($_SESSION['employee_id'], $newPassword)) {
            throw new RuntimeException('Unable to update the account password.');
        }

        session_regenerate_id(true);
        $this->setFlash('success', 'Your password has been updated.');
        $this->redirect('settings');
    }
}
