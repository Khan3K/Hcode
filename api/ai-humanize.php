<?php

require_once __DIR__ . '/../inc/AIEngine.php';
require_once __DIR__ . '/../inc/Humanizer.php';
require_once __DIR__ . '/../inc/Config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
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
$intensity = isset($input['intensity']) ? max(10, min(100, (int)$input['intensity'])) : 65;
$useAi = isset($input['use_ai']) ? (bool)$input['use_ai'] : false;

$validLanguages = ['javascript','python','cpp','java','php','csharp','go','rust','ruby','swift','typescript'];
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
$configArr = ['version' => '2.0.0'];
$humanizer = new Humanizer($configArr);

try {
    $humanized = $humanizer->humanize($code, $language, $intensity);
    $usedAi = false;
    $aiProvider = '';

    if ($useAi) {
        $cfg = new Config();
        $settings = $cfg->load();
        $provider = $settings['provider'] ?? 'pollinations';

        $needsKey = Config::providerNeedsKey($provider);
        $hasKey = false;
        if ($needsKey === 'optional') {
            $keyField = $provider . '_key';
            $hasKey = !empty(trim($settings[$keyField] ?? ''));
            // Optional key providers always work (with or without key)
            $hasKey = true;
        } elseif ($needsKey === 'required') {
            $keyField = $provider . '_key';
            $hasKey = !empty(trim($settings[$keyField] ?? ''));
        } else {
            $hasKey = true;
        }

        if ($hasKey) {
            $aiEngine = new AIEngine($provider);
            $result = $aiEngine->humanize($humanized, $language, $intensity);
            if ($result['success']) {
                $humanized = $result['humanized'];
                $usedAi = true;
                $aiProvider = $provider;
            }
        }
    }

    $executionTime = round((microtime(true) - $startTime) * 1000, 2);
    $origLines = explode("\n", $code);
    $newLines = explode("\n", $humanized);
    $changes = count(array_diff($newLines, $origLines));

    echo json_encode([
        'success' => true,
        'original' => $code,
        'humanized' => $humanized,
        'language' => $language,
        'intensity' => $intensity,
        'changes' => $changes,
        'execution_time' => $executionTime . 'ms',
        'ai_enhanced' => $usedAi,
        'ai_provider' => $aiProvider,
        'stats' => [
            'original_lines' => count($origLines),
            'humanized_lines' => count($newLines),
            'original_chars' => strlen($code),
            'humanized_chars' => strlen($humanized),
        ],
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Humanization failed: ' . $e->getMessage()]);
}
