<?php
/**
 * CMD - Mushroom Management System
 * Application entry point / bootstrap
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// ---- Paths ----
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');

// ---- Base URL (auto-detected; adjust if deploying to a subfolder) ----
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', rtrim($scriptDir, '/'));

// ---- App name ----
define('APP_NAME', 'CMD');
define('APP_FULL_NAME', 'Cultivation & Mushroom Data Management System');

// ---- Config & core ----
require_once CONFIG_PATH . '/database.php';
require_once APP_PATH . '/helpers.php';
require_once APP_PATH . '/core/Model.php';
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/core/App.php';

// ---- Dispatch ----
new App();
