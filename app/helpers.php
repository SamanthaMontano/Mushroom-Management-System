<?php
/**
 * Global helper functions available in every view/controller.
 */

function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function peso($amount): string
{
    return '₱' . number_format((float)$amount, 2);
}

function niceDate($date, string $format = 'M d, Y'): string
{
    if (!$date) return '—';
    $ts = is_numeric($date) ? $date : strtotime($date);
    return date($format, $ts);
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}

function verifyCsrf(): bool
{
    return isset($_POST['csrf_token']) && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

function activeNav(string $segment): string
{
    $current = $_GET['url'] ?? 'dashboard';
    $first = explode('/', $current)[0];
    return strtolower($first) === strtolower($segment) ? 'active' : '';
}

function statusBadge(string $status): string
{
    $map = [
        'active' => 'success', 'inactive' => 'secondary',
        'In Stock' => 'success', 'Low Stock' => 'warning', 'Out of Stock' => 'danger',
        'Incubation' => 'info', 'Fruiting' => 'primary', 'Harvesting' => 'warning',
        'Completed' => 'success', 'Failed' => 'danger',
        'Paid' => 'success', 'Pending' => 'warning', 'Partial' => 'info',
        'Delivered' => 'success', 'In Transit' => 'primary', 'Cancelled' => 'danger',
        'In Progress' => 'warning',
        'A' => 'success', 'B' => 'info', 'C' => 'warning', 'Reject' => 'danger',
    ];
    $color = $map[$status] ?? 'secondary';
    return '<span class="badge status-badge status-badge-' . $color . '">' . e($status) . '</span>';
}

function flashMessage(): string
{
    if (empty($_SESSION['flash'])) return '';
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return '<div class="alert alert-' . e($flash['type']) . ' alert-dismissible fade show" role="alert">'
        . e($flash['message'])
        . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

function oldOr($value, $fallback = '')
{
    return $value !== null && $value !== '' ? $value : $fallback;
}
