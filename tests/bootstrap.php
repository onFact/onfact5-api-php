<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__ . DS . '..' . DS);

require_once(ROOT . 'vendor' . DS . 'autoload.php');

// Load env vars for testing
if (file_exists(ROOT . '.env') && !getenv('API_KEY')) {
    $dotenv = Dotenv\Dotenv::createImmutable(ROOT);
    $dotenv->load();
}
