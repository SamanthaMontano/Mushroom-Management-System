<?php
/**
 * Base Controller - view rendering, model loading, auth guard, flash messages.
 */
class Controller
{
    public function __construct()
    {
        $this->requireAuth();
    }

    protected function model(string $model)
    {
        require_once APP_PATH . '/models/' . $model . '.php';
        return new $model();
    }

    protected function view(string $view, array $data = [])
    {
        extract($data);
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            die("View not found: {$view}");
        }
        require APP_PATH . '/views/layouts/header.php';
        require APP_PATH . '/views/layouts/sidebar.php';
        require $viewFile;
        require APP_PATH . '/views/layouts/footer.php';
    }

    protected function bareView(string $view, array $data = [])
    {
        extract($data);
        require APP_PATH . '/views/' . $view . '.php';
    }

    protected function redirect(string $path)
    {
        header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
        exit;
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function input(string $key, $default = null)
    {
        return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
    }

    protected function setFlash(string $type, string $message)
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    protected function requireAuth()
    {
        // Login/logout controllers must not require auth (avoid redirect loop)
        $exempt = ['AuthController'];
        if (in_array(static::class, $exempt)) return;

        if (empty($_SESSION['employee_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT role, status FROM employees WHERE employee_id = :employee_id LIMIT 1'
        );
        $stmt->execute(['employee_id' => $_SESSION['employee_id']]);
        $account = $stmt->fetch();

        if (!$account || $account['status'] !== 'active') {
            $_SESSION = [];
            session_destroy();
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $_SESSION['role'] = $account['role'];
        $_SESSION['account_type'] = $account['role'] === 'admin' ? 'owner' : 'employee';
    }

    protected function requireRole(array $roles)
    {
        if (empty($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
            $this->setFlash('danger', 'You do not have permission to access that page.');
            $this->redirect('dashboard');
        }
    }

    protected function isOwner(): bool
    {
        return ($_SESSION['role'] ?? '') === 'admin';
    }

    protected function requireOwner(): void
    {
        if (!$this->isOwner()) {
            $this->setFlash('danger', 'This area is available to owners only.');
            $this->redirect('dashboard');
        }
    }
}
