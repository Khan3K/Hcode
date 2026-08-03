<?php

class Config {
    private string $configDir;
    private string $configFile;
    private string $encryptionKey;

    public function __construct() {
        $this->configDir = __DIR__ . '/../data';
        $this->configFile = $this->configDir . '/config.json';
        $this->encryptionKey = $this->deriveKey();
    }

    private function deriveKey(): string {
        $seed = $_SERVER['SERVER_SOFTWARE'] ?? 'HCODE_KEY';
        $seed .= $_SERVER['SERVER_ADDR'] ?? '127.0.0.1';
        $seed .= __FILE__;
        return hash('sha256', $seed, true);
    }

    public function encrypt(string $plaintext): string {
        if (empty($plaintext)) return '';
        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = @openssl_encrypt($plaintext, 'aes-256-cbc', $this->encryptionKey, OPENSSL_RAW_DATA, $iv);
        return $encrypted !== false ? base64_encode($iv . $encrypted) : base64_encode($plaintext);
    }

    public function decrypt(string $encoded): string {
        if (empty($encoded)) return '';
        $data = base64_decode($encoded);
        if ($data === false || strlen($data) < 16) return $encoded;
        $iv = substr($data, 0, 16);
        $ciphertext = substr($data, 16);
        $decrypted = @openssl_decrypt($ciphertext, 'aes-256-cbc', $this->encryptionKey, OPENSSL_RAW_DATA, $iv);
        return $decrypted !== false ? $decrypted : $encoded;
    }

    public function load(): array {
        if (!file_exists($this->configFile)) {
            return $this->defaults();
        }
        $contents = @file_get_contents($this->configFile);
        if ($contents === false) return $this->defaults();
        $data = json_decode($contents, true);
        if (!is_array($data)) return $this->defaults();

        if (!empty($data['openai_key'])) $data['openai_key'] = $this->decrypt($data['openai_key']);
        if (!empty($data['anthropic_key'])) $data['anthropic_key'] = $this->decrypt($data['anthropic_key']);
        if (!empty($data['gemini_key'])) $data['gemini_key'] = $this->decrypt($data['gemini_key']);
        if (!empty($data['deepseek_key'])) $data['deepseek_key'] = $this->decrypt($data['deepseek_key']);
        if (!empty($data['groq_key'])) $data['groq_key'] = $this->decrypt($data['groq_key']);
        if (!empty($data['together_key'])) $data['together_key'] = $this->decrypt($data['together_key']);
        if (!empty($data['deepinfra_key'])) $data['deepinfra_key'] = $this->decrypt($data['deepinfra_key']);
        if (!empty($data['openrouter_key'])) $data['openrouter_key'] = $this->decrypt($data['openrouter_key']);

        return array_merge($this->defaults(), $data);
    }

    public function save(array $data): bool {
        $current = $this->load();
        $merged = array_merge($current, $data);

        $keyFields = ['openai_key', 'anthropic_key', 'gemini_key', 'deepseek_key',
                       'groq_key', 'together_key', 'deepinfra_key', 'openrouter_key'];
        foreach ($keyFields as $kf) {
            if (!empty($merged[$kf]) && $merged[$kf] !== $this->decrypt($current[$kf] ?? '')) {
                $merged[$kf] = $this->encrypt($merged[$kf]);
            }
        }

        if (!is_dir($this->configDir)) @mkdir($this->configDir, 0755, true);

        $json = json_encode($merged, JSON_PRETTY_PRINT);
        if ($json === false) return false;

        $written = @file_put_contents($this->configFile, $json, LOCK_EX);
        return $written !== false ? (bool)@chmod($this->configFile, 0600) : false;
    }

    private function defaults(): array {
        return [
            'provider' => 'pollinations',
            'model' => '',
            'ai_enhance' => true,
            'ai_temperature' => 0.9,
            'token_budget' => 0,
            'system_prompt' => '',
            'max_tokens' => 4096,
            'openai_key' => '',
            'openai_model' => 'gpt-4o-mini',
            'anthropic_key' => '',
            'anthropic_model' => 'claude-3-5-haiku-20241022',
            'gemini_key' => '',
            'gemini_model' => 'gemini-2.0-flash',
            'deepseek_key' => '',
            'deepseek_model' => 'deepseek-chat',
            'groq_key' => '',
            'groq_model' => 'llama-3.3-70b-versatile',
            'together_key' => '',
            'together_model' => 'meta-llama/Meta-Llama-3.1-8B-Instruct-Turbo',
            'deepinfra_key' => '',
            'deepinfra_model' => 'meta-llama/Meta-Llama-3.1-8B-Instruct',
            'openrouter_key' => '',
            'openrouter_model' => 'meta-llama/llama-3.1-8b-instruct:free',
        ];
    }

    public static function getModelsFor(string $provider): array {
        $models = [
            'openai'      => ['gpt-4o', 'gpt-4o-mini', 'gpt-4-turbo', 'gpt-3.5-turbo'],
            'anthropic'   => ['claude-sonnet-4-20250514', 'claude-3-5-sonnet-20241022', 'claude-3-5-haiku-20241022'],
            'gemini'      => ['gemini-2.0-flash', 'gemini-1.5-pro', 'gemini-1.5-flash'],
            'deepseek'    => ['deepseek-chat', 'deepseek-reasoner'],
            'groq'        => ['llama-3.3-70b-versatile', 'llama-3.1-8b-instant', 'mixtral-8x7b-32768', 'gemma2-9b-it'],
            'together'    => ['meta-llama/Meta-Llama-3.1-8B-Instruct-Turbo', 'meta-llama/Meta-Llama-3.1-70B-Instruct-Turbo', 'mistralai/Mixtral-8x7B-Instruct-v0.1', 'Qwen/Qwen2.5-7B-Instruct-Turbo'],
            'deepinfra'   => ['meta-llama/Meta-Llama-3.1-8B-Instruct', 'meta-llama/Meta-Llama-3.1-70B-Instruct', 'mistralai/Mixtral-8x7B-Instruct-v0.1', 'microsoft/Phi-3.5-mini-instruct', 'Qwen/Qwen2.5-7B-Instruct'],
            'openrouter'  => ['meta-llama/llama-3.1-8b-instruct:free', 'openai/gpt-4o-mini', 'anthropic/claude-3.5-sonnet', 'mistralai/mistral-7b-instruct:free', 'google/gemma-2-9b-it:free'],
            'pollinations' => ['openai', 'llama', 'deepseek', 'mistral'],
            'puter'       => ['gemini-3.5-flash', 'gpt-4o', 'claude-sonnet-4-20250514'],
        ];
        return $models[$provider] ?? ['gpt-4o-mini'];
    }

    public static function providerNeedsKey(string $provider): string {
        $noKey = ['pollinations', 'puter', 'browser'];
        $optional = ['groq', 'together', 'deepinfra', 'openrouter'];
        if (in_array($provider, $noKey)) return 'none';
        if (in_array($provider, $optional)) return 'optional';
        return 'required';
    }

    public static function providerInfo(string $provider): array {
        $info = [
            'pollinations' => ['cat' => 'zeroconfig', 'label' => 'Pollinations', 'icon' => 'cloud', 'desc' => 'Free open API. Good for testing. Rate limited.', 'color' => '#10b981'],
            'puter'       => ['cat' => 'zeroconfig', 'label' => 'Puter.js', 'icon' => 'bolt', 'desc' => 'Runs in browser via Puter. Best quality free option.', 'color' => '#6366f1'],
            'browser'     => ['cat' => 'zeroconfig', 'label' => 'Browser', 'icon' => 'globe', 'desc' => 'Tiny local model via browser. No server call.', 'color' => '#8b5cf6'],
            'groq'        => ['cat' => 'freeserver', 'label' => 'Groq', 'icon' => 'rocket', 'desc' => 'Ultra-fast inference. Llama 3.3 70B, Mixtral, Gemma.', 'color' => '#f43f5e'],
            'together'    => ['cat' => 'freeserver', 'label' => 'Together AI', 'icon' => 'users', 'desc' => 'Llama, Mixtral, Qwen models. Serverless.', 'color' => '#3b82f6'],
            'deepinfra'   => ['cat' => 'freeserver', 'label' => 'DeepInfra', 'icon' => 'server', 'desc' => 'Serverless LLMs. Llama, Mixtral, Phi, Qwen.', 'color' => '#8b5cf6'],
            'openrouter'  => ['cat' => 'freeserver', 'label' => 'OpenRouter', 'icon' => 'route', 'desc' => 'Access 100+ models. Many free options.', 'color' => '#f59e0b'],
            'gemini'      => ['cat' => 'paid', 'label' => 'Gemini', 'icon' => 'sparkles', 'desc' => 'Google Gemini 2.0 Flash / 1.5 Pro. Free tier via AI Studio.', 'color' => '#4285f4'],
            'openai'      => ['cat' => 'paid', 'label' => 'OpenAI', 'icon' => 'openai', 'desc' => 'GPT-4o, GPT-4o-mini, GPT-4 Turbo. Industry standard.', 'color' => '#10a37f'],
            'claude'      => ['cat' => 'paid', 'label' => 'Claude', 'icon' => 'feather', 'desc' => 'Claude 3.5 Sonnet/Haiku, Claude Sonnet 4. Best reasoning.', 'color' => '#d97706'],
            'deepseek'    => ['cat' => 'paid', 'label' => 'DeepSeek', 'icon' => 'dragon', 'desc' => 'DeepSeek V3 / R1. Cost-effective coding.', 'color' => '#4f46e5'],
        ];
        return $info[$provider] ?? ['cat' => 'paid', 'label' => $provider, 'icon' => 'brain', 'desc' => '', 'color' => '#6366f1'];
    }
}
