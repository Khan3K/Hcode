<?php
require_once __DIR__ . '/../inc/Config.php';

header('Content-Type: application/json');

try {
    $config = (new Config())->load();
    $provider = $config['provider'] ?? 'unknown';
    echo json_encode([
        'status' => 'ok',
        'service' => 'H-Code',
        'version' => '2.0.0',
        'provider' => $provider,
        'time' => gmdate('c'),
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'error' => $e->getMessage()]);
}
