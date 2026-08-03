<?php
require_once __DIR__ . '/inc/Humanizer.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['code']) || !isset($input['language'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields: code, language']);
    exit;
}

$code = $input['code'];
$language = $input['language'];
$intensity = isset($input['intensity']) ? (int)$input['intensity'] : 50;

$intensity = max(10, min(100, $intensity));

$validLanguages = ['javascript', 'python', 'cpp', 'java', 'php', 'csharp', 'go', 'rust', 'ruby', 'swift', 'typescript'];
if (!in_array($language, $validLanguages)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid language']);
    exit;
}

if (empty(trim($code))) {
    http_response_code(400);
    echo json_encode(['error' => 'Code cannot be empty']);
    exit;
}

$startTime = microtime(true);

$config = [
    'version' => '2.0.0',
    'max_code_length' => 100000,
];

if (strlen($code) > $config['max_code_length']) {
    http_response_code(400);
    echo json_encode(['error' => 'Code too long (max 100KB)']);
    exit;
}

$humanizer = new Humanizer($config);

try {
    $humanized = $humanizer->humanize($code, $language, $intensity);
    $executionTime = round((microtime(true) - $startTime) * 1000, 2);

    $changes = count(array_diff(explode("\n", $humanized), explode("\n", $code)));

    echo json_encode([
        'success' => true,
        'original' => $code,
        'humanized' => $humanized,
        'language' => $language,
        'intensity' => $intensity,
        'changes' => $changes,
        'execution_time' => $executionTime . 'ms',
        'stats' => [
            'original_lines' => count(explode("\n", $code)),
            'humanized_lines' => count(explode("\n", $humanized)),
            'original_chars' => strlen($code),
            'humanized_chars' => strlen($humanized),
        ],
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Humanization failed: ' . $e->getMessage()]);
}
