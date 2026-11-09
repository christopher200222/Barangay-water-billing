<?php
// Health check for the cloud host (and uptime monitors). Says OK only when the database answers. Reveals nothing else.
header('Content-Type: text/plain'); header('Cache-Control: no-store');
try {
    require_once __DIR__ . '/config/database.php';
    get_db_connection()->query('SELECT 1');
    echo 'OK';
} catch (Throwable $e) {
    http_response_code(503);
    echo 'DB DOWN';
}
