<?php

require_once __DIR__ . '/Config.php';

class AIEngine {
    private Config $config;
    private array $settings;
    private string $provider;

    public function __construct(string $provider = '') {
        $this->config = new Config();
        $this->settings = $this->config->load();
        $this->provider = $provider ?: ($this->settings['provider'] ?? 'pollinations');
    }

    public function humanize(string $code, string $language, int $intensity): array {
        $prompt = $this->buildPrompt($code, $language, $intensity);
        return $this->routeRequest($prompt);
    }

    private function buildPrompt(string $code, string $language, int $intensity): string {
        $aggression = match(true) {
            $intensity >= 80 => 'AGGRESSIVE — apply every technique, make it look like a tired developer wrote it at 2am',
            $intensity >= 55 => 'MODERATE — several techniques, noticeable inconsistencies',
            default => 'LIGHT — subtle variations, minimal changes',
        };

        $langName = match($language) {
            'cpp' => 'C++', 'csharp' => 'C#', 'javascript' => 'JavaScript',
            'typescript' => 'TypeScript', 'python' => 'Python', 'ruby' => 'Ruby',
            'swift' => 'Swift', default => ucfirst($language),
        };

        $sysPrompt = trim($this->settings['system_prompt'] ?? '');
        $extra = $sysPrompt ? "\n\nUser additional instructions:\n{$sysPrompt}\n" : '';

        $tempNote = '';
        if ($intensity >= 80) {
            $tempNote = ' - ERR on the side of messiness';
        }

        return <<<PROMPT
You are rewriting {$langName} code to bypass AI code detectors by making it look human-written.

Level: {$aggression}{$tempNote}

CRITICAL RULES — YOU MUST FOLLOW:
- Output ONLY the rewritten code in a ```{$language} code block. No introductions, no explanations.
- Code MUST be 100% syntactically valid and functionally identical to the original
- Do NOT change logic, algorithms, behavior, or introduce real bugs

WHAT MAKES CODE LOOK HUMAN (apply these):
1. Inconsistent formatting — vary spacing around operators; sometimes `x+1`, sometimes `x + 1`
2. Mixed naming styles — some camelCase, some snake_case, short names like `x`/`tmp`, long names like `accumulatedResult`
3. Redundant parentheses — `return (x + y)` instead of `return x + y`
4. Occasional Yoda conditions — `if (null === x)` instead of `if (x === null)`
5. Context-specific comments that reference actual variable/function names from the code
6. A forgotten debug log (console.log / print / etc) that was used during development
7. Commented-out alternate implementation left behind: "// was using reduce here but changed my mind"
8. Dead variable assignments that look like the developer was testing something
9. Mix of single and double quotes randomly
10. Trailing whitespace on some lines

ANTI-PATTERNS TO AVOID (these scream AI):
- Do NOT add templated comments like "TODO: refactor this later" or "FIXME: this is hacky"
- Do NOT add "// unused" markers — dead code should look like the developer forgot to delete it, not like it was inserted
- Do NOT keep perfect consistent spacing everywhere
- Do NOT rename variables to "foo" or "bar" — use names that fit the context
- Do NOT add the same type of transformation on every line — be INCONSISTENT
- Do NOT over-comment — real developers comment sparingly and only on tricky parts
- Do NOT explain what the code does — just rewrite it{$extra}

Code:
```{$language}
{$code}
```
PROMPT;
    }

    private function routeRequest(string $prompt): array {
        return match($this->provider) {
            'openai'      => $this->callOpenAI($prompt),
            'anthropic',
            'claude'      => $this->callAnthropic($prompt),
            'gemini'      => $this->callGemini($prompt),
            'deepseek'    => $this->callDeepSeek($prompt),
            'groq'        => $this->callGroq($prompt),
            'together'    => $this->callTogether($prompt),
            'deepinfra'   => $this->callDeepInfra($prompt),
            'openrouter'  => $this->callOpenRouter($prompt),
            'pollinations' => $this->callPollinations($prompt),
            'puter'       => $this->callPuter($prompt),
            default       => $this->callPollinations($prompt),
        };
    }

    private function getApiKey(string $keyName): string {
        return trim($this->settings[$keyName] ?? '');
    }

    private function getModel(): string {
        $key = $this->provider . '_model';
        return $this->settings[$key] ?? $this->settings['model'] ?? '';
    }

    private function post(string $url, array $payload, array $headers): array {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 120,
            CURLOPT_CONNECTTIMEOUT => 15,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        return ['body' => $response, 'http' => $httpCode, 'error' => $error];
    }

    private function buildMessages(string $prompt): array {
        $sysPrompt = trim($this->settings['system_prompt'] ?? '');
        $systemContent = 'You are a code humanization engine. Return ONLY the humanized code in a markdown code block. Make it look like a human wrote it — inconsistent spacing, mixed naming styles, redundant parentheses, Yoda conditions, forgotten debug logs, and context-specific comments that reference actual identifiers. AVOID templated TODO/FIXME comments. NEVER include explanations.';
        if ($sysPrompt) {
            $systemContent .= ' ' . $sysPrompt;
        }
        return [
            ['role' => 'system', 'content' => $systemContent],
            ['role' => 'user', 'content' => $prompt],
        ];
    }

    // === OPENAI ===
    private function callOpenAI(string $prompt): array {
        $key = $this->getApiKey('openai_key');
        if (empty($key)) return $this->noKeyError('OpenAI');

        $result = $this->post('https://api.openai.com/v1/chat/completions', [
            'model' => $this->getModel() ?: 'gpt-4o-mini',
            'messages' => $this->buildMessages($prompt),
            'temperature' => (float)($this->settings['ai_temperature'] ?? 0.7),
            'max_tokens' => (int)($this->settings['max_tokens'] ?? 4096),
        ], [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $key,
        ]);

        return $this->parseOpenAICompat($result);
    }

    // === ANTHROPIC ===
    private function callAnthropic(string $prompt): array {
        $key = $this->provider === 'claude'
            ? $this->getApiKey('anthropic_key')
            : $this->getApiKey('anthropic_key');
        if (empty($key)) return $this->noKeyError('Anthropic');

        $systemContent = 'You are a code humanization engine. Return ONLY the humanized code in a markdown code block. Make it look like a human wrote it — inconsistent spacing, mixed naming styles, redundant parentheses, Yoda conditions, forgotten debug logs, and context-specific comments that reference actual identifiers. AVOID templated TODO/FIXME comments.';
        $sysPrompt = trim($this->settings['system_prompt'] ?? '');
        if ($sysPrompt) $systemContent .= ' ' . $sysPrompt;

        $result = $this->post('https://api.anthropic.com/v1/messages', [
            'model' => $this->getModel() ?: 'claude-3-5-haiku-20241022',
            'max_tokens' => (int)($this->settings['max_tokens'] ?? 4096),
            'temperature' => (float)($this->settings['ai_temperature'] ?? 0.7),
            'system' => $systemContent,
            'messages' => [['role' => 'user', 'content' => $prompt]],
        ], [
            'Content-Type: application/json',
            'x-api-key: ' . $key,
            'anthropic-version: 2023-06-01',
        ]);

        if ($result['error']) return ['success' => false, 'error' => 'CURL: ' . $result['error']];
        $data = json_decode($result['body'], true);
        if (!$data || $result['http'] !== 200) {
            return ['success' => false, 'error' => 'Anthropic: ' . ($data['error']['message'] ?? 'HTTP ' . $result['http'])];
        }
        $content = '';
        foreach ($data['content'] as $block) {
            if ($block['type'] === 'text') $content .= $block['text'];
        }
        return $this->extractCode($content);
    }

    // === GEMINI ===
    private function callGemini(string $prompt): array {
        $key = $this->getApiKey('gemini_key');
        if (empty($key)) return $this->noKeyError('Gemini');

        $model = $this->getModel() ?: 'gemini-2.0-flash';
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}";

        $result = $this->post($url, [
            'contents' => [['parts' => [['text' => $prompt]]]],
            'generationConfig' => [
                'temperature' => (float)($this->settings['ai_temperature'] ?? 0.7),
                'maxOutputTokens' => (int)($this->settings['max_tokens'] ?? 4096),
            ],
        ], ['Content-Type: application/json']);

        if ($result['error']) return ['success' => false, 'error' => 'CURL: ' . $result['error']];
        $data = json_decode($result['body'], true);
        if (!$data || $result['http'] !== 200) {
            return ['success' => false, 'error' => 'Gemini: ' . ($data['error']['message'] ?? 'HTTP ' . $result['http'])];
        }
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
        return $this->extractCode($text);
    }

    // === DEEPSEEK ===
    private function callDeepSeek(string $prompt): array {
        $key = $this->getApiKey('deepseek_key');
        if (empty($key)) return $this->noKeyError('DeepSeek');

        $result = $this->post('https://api.deepseek.com/chat/completions', [
            'model' => $this->getModel() ?: 'deepseek-chat',
            'messages' => $this->buildMessages($prompt),
            'temperature' => (float)($this->settings['ai_temperature'] ?? 0.7),
            'max_tokens' => (int)($this->settings['max_tokens'] ?? 4096),
        ], [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $key,
        ]);

        return $this->parseOpenAICompat($result);
    }

    // === GROQ ===
    private function callGroq(string $prompt): array {
        $key = $this->getApiKey('groq_key');
        if (empty($key)) return $this->noKeyError('Groq');

        $result = $this->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => $this->getModel() ?: 'llama-3.3-70b-versatile',
            'messages' => $this->buildMessages($prompt),
            'temperature' => (float)($this->settings['ai_temperature'] ?? 0.7),
            'max_tokens' => (int)($this->settings['max_tokens'] ?? 4096),
        ], [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $key,
        ]);

        return $this->parseOpenAICompat($result);
    }

    // === TOGETHER AI ===
    private function callTogether(string $prompt): array {
        $key = $this->getApiKey('together_key');
        if (empty($key)) return $this->noKeyError('Together AI');

        $result = $this->post('https://api.together.xyz/v1/chat/completions', [
            'model' => $this->getModel() ?: 'meta-llama/Meta-Llama-3.1-8B-Instruct-Turbo',
            'messages' => $this->buildMessages($prompt),
            'temperature' => (float)($this->settings['ai_temperature'] ?? 0.7),
            'max_tokens' => (int)($this->settings['max_tokens'] ?? 4096),
        ], [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $key,
        ]);

        return $this->parseOpenAICompat($result);
    }

    // === DEEPINFRA ===
    private function callDeepInfra(string $prompt): array {
        $key = $this->getApiKey('deepinfra_key');
        if (empty($key)) return $this->noKeyError('DeepInfra');

        $result = $this->post('https://api.deepinfra.com/v1/openai/chat/completions', [
            'model' => $this->getModel() ?: 'meta-llama/Meta-Llama-3.1-8B-Instruct',
            'messages' => $this->buildMessages($prompt),
            'temperature' => (float)($this->settings['ai_temperature'] ?? 0.7),
            'max_tokens' => (int)($this->settings['max_tokens'] ?? 4096),
        ], [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $key,
        ]);

        return $this->parseOpenAICompat($result);
    }

    // === OPENROUTER ===
    private function callOpenRouter(string $prompt): array {
        $key = $this->getApiKey('openrouter_key');
        if (empty($key)) return $this->noKeyError('OpenRouter');

        $result = $this->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => $this->getModel() ?: 'meta-llama/llama-3.1-8b-instruct:free',
            'messages' => $this->buildMessages($prompt),
            'temperature' => (float)($this->settings['ai_temperature'] ?? 0.7),
            'max_tokens' => (int)($this->settings['max_tokens'] ?? 4096),
        ], [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $key,
            'HTTP-Referer: http://localhost/Hcode',
        ]);

        return $this->parseOpenAICompat($result);
    }

    // === POLLINATIONS (FREE - no key needed) ===
    private function callPollinations(string $prompt): array {
        $model = $this->getModel() ?: 'openai';
        $temp = (float)($this->settings['ai_temperature'] ?? 0.9);
        $systemMsg = 'You are a code humanization engine. Return ONLY the humanized code in a markdown code block. Make it look like a human wrote it — inconsistent formatting, mixed naming styles, redundant parens, and realistic comments. Never include explanations.';
        $url = "https://text.pollinations.ai/?model={$model}&system=" . urlencode($systemMsg) . "&json=true";

        $result = $this->post($url, [
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => $temp,
        ], ['Content-Type: application/json']);

        if ($result['error']) return ['success' => false, 'error' => 'Pollinations: ' . $result['error']];
        $data = json_decode($result['body'], true);
        $text = is_string($data) ? $data : ($data['choices'][0]['message']['content'] ?? $data['text'] ?? '');
        return $this->extractCode($text);
    }

    // === PUTER (FREE - calls browser-side, client-only stub) ===
    private function callPuter(string $prompt): array {
        return ['success' => false, 'error' => 'Puter.js runs in-browser only. Select another provider for server-side use.'];
    }

    // === PARSERS ===
    private function parseOpenAICompat(array $result): array {
        if ($result['error']) return ['success' => false, 'error' => 'CURL: ' . $result['error']];
        $data = json_decode($result['body'], true);
        if (!$data || $result['http'] !== 200) {
            return ['success' => false, 'error' => ($data['error']['message'] ?? 'HTTP ' . $result['http'])];
        }
        $content = $data['choices'][0]['message']['content'] ?? '';
        return $this->extractCode($content);
    }

    private function extractCode(string $content): array {
        preg_match('/```(?:\w+)?\s*\n?(.*?)```/s', $content, $m);
        $code = !empty($m[1]) ? trim($m[1]) : trim($content);
        if (empty($code)) return ['success' => false, 'error' => 'AI returned empty response'];
        return ['success' => true, 'humanized' => $code];
    }

    private function noKeyError(string $name): array {
        return ['success' => false, 'error' => "{$name} API key not configured. Add it in Settings > AI Engine Configuration."];
    }
}
