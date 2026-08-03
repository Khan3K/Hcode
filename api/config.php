<?php

require_once __DIR__ . '/../inc/Config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

$config = new Config();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
        exit;
    }

    $allowed = ['provider', 'model', 'ai_enhance', 'ai_temperature', 'system_prompt', 'max_tokens',
        'openai_key', 'openai_model', 'anthropic_key', 'anthropic_model',
        'gemini_key', 'gemini_model', 'deepseek_key', 'deepseek_model',
        'groq_key', 'groq_model', 'together_key', 'together_model',
        'deepinfra_key', 'deepinfra_model', 'openrouter_key', 'openrouter_model',
        'pollinations_model', 'puter_model'];

    $saveData = [];
    foreach ($allowed as $key) {
        if (isset($input[$key])) {
            $saveData[$key] = $input[$key];
        }
    }

    // Handle provider-specific model field
    if (!isset($saveData['model']) && isset($input['provider'])) {
        $modelField = $input['provider'] . '_model';
        if (isset($input[$modelField])) {
            $saveData['model'] = $input[$modelField];
        }
    }

    if ($config->save($saveData)) {
        echo json_encode(['success' => true, 'message' => 'Configuration saved']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to save configuration']);
    }
    exit;
}

// GET - return config with keys masked
$settings = $config->load();
$masked = ['success' => true];

$maskedFields = ['provider', 'model', 'ai_enhance', 'ai_temperature', 'system_prompt', 'max_tokens',
    'openai_model', 'anthropic_model', 'gemini_model', 'deepseek_model',
    'groq_model', 'together_model', 'deepinfra_model', 'openrouter_model'];

foreach ($maskedFields as $f) {
    $masked[$f] = $settings[$f] ?? '';
}

// Mask API keys - show first 8 chars only
$keyFields = ['openai_key', 'anthropic_key', 'gemini_key', 'deepseek_key',
              'groq_key', 'together_key', 'deepinfra_key', 'openrouter_key'];
foreach ($keyFields as $kf) {
    $val = $settings[$kf] ?? '';
    $masked[$kf] = !empty($val) ? substr($val, 0, 8) . '...' : '';
}

// Also return models list for UI
$masked['models'] = [];
if (!empty($settings['provider'])) {
    $masked['models'] = Config::getModelsFor($settings['provider']);
}

$masked['needs_key'] = Config::providerNeedsKey($settings['provider'] ?? 'pollinations');
$masked['provider_info'] = Config::providerInfo($settings['provider'] ?? 'pollinations');

echo json_encode($masked);
