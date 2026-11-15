<?php
class AuthController extends Controller
{
    public function __construct()
    {
        // Intentionally skip parent::__construct() -- login page must be public.
    }

    public function login()
    {
        if (!empty($_SESSION['employee_id'])) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $error = null;
        $selectedLoginType = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) && is_string($_POST['username'])
                ? trim($_POST['username'])
                : '';
            $password = isset($_POST['password']) && is_string($_POST['password'])
                ? $_POST['password']
                : '';
            $selectedLoginType = isset($_POST['login_type']) && is_string($_POST['login_type'])
                ? $_POST['login_type']
                : '';

            $employeeModel = $this->model('Employee');
            $employee = $employeeModel->attemptLogin($username, $password);
            $accountType = $employee && $employee['role'] === 'admin' ? 'owner' : 'employee';

            if (
                $employee &&
                $employee['status'] === 'active' &&
                in_array($selectedLoginType, ['owner', 'employee'], true) &&
                $selectedLoginType === $accountType
            ) {
                session_regenerate_id(true);
                $_SESSION['employee_id'] = $employee['employee_id'];
                $_SESSION['employee_name'] = $employee['first_name'] . ' ' . $employee['last_name'];
                $_SESSION['role'] = $employee['role'];
                $_SESSION['account_type'] = $accountType;
                $_SESSION['position'] = $employee['position'];
                header('Location: ' . BASE_URL . '/dashboard');
                exit;
            }
            $error = 'Sign-in failed. Check your username, password, account type, and account status.';
        }

        $this->bareView('auth/login', [
            'error' => $error,
            'selectedLoginType' => $selectedLoginType,
        ]);
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }
}
