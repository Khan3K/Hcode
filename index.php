<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>H-Code Humanizer - Make AI Code Undetectable</title>
<link rel="stylesheet" href="css/style.css">
<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⚡</text></svg>">
</head>
<body>
<div class="noise"></div>

<nav class="navbar">
    <div class="nav-left">
        <span class="logo">H<span class="accent">-</span>CODE</span>
        <span class="tagline">AI Code Humanizer</span>
    </div>
    <div class="nav-right">
        <div class="status-badge" id="statusBadge">
            <span class="status-dot"></span>
            <span>Ready</span>
        </div>
        <button class="btn-nav" id="settingsBtn" title="AI Engine Configuration">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        </button>
        <button class="theme-toggle" id="themeToggle" title="Toggle theme">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        </button>
    </div>
</nav>

<main class="main-container">
    <div class="header-section">
        <h1>Make AI Code <span class="gradient-text">Undetectable</span></h1>
        <p>Advanced neural humanization engine — transform AI-generated code into authentic human-written code that bypasses all major AI detectors</p>
    </div>

    <div class="editor-container">
        <div class="editor-side">
            <div class="editor-header">
                <div class="tabs">
                    <button class="tab active" data-tab="input">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                        Input Code
                    </button>
                    <button class="tab" data-tab="output">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                        Humanized
                    </button>
                    <button class="tab" data-tab="diff">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Diff View
                    </button>
                </div>
                <div class="editor-actions">
                    <button class="btn btn-icon" id="loadSample" title="Load sample code">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    </button>
                    <button class="btn btn-icon" id="clearCode" title="Clear">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </button>
                </div>
            </div>

            <div class="tab-content" id="tab-input">
                <div class="code-editor-wrapper">
                    <div class="line-numbers" id="lineNumbers">1</div>
                    <textarea id="codeInput" class="code-editor" placeholder="Paste your AI-generated code here..." spellcheck="false" autocomplete="off"></textarea>
                </div>
            </div>

            <div class="tab-content hidden" id="tab-output">
                <div class="code-editor-wrapper">
                    <div class="line-numbers" id="outputLineNumbers">1</div>
                    <textarea id="codeOutput" class="code-editor" readonly spellcheck="false"></textarea>
                </div>
                <div class="output-actions" id="outputActions" style="display:none;">
                    <button class="btn btn-sm" id="copyResult">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Copy
                    </button>
                    <button class="btn btn-sm" id="downloadResult">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Download
                    </button>
                </div>
            </div>

            <div class="tab-content hidden" id="tab-diff">
                <div class="diff-container" id="diffContainer">
                    <div class="diff-empty">Humanize code to see the diff view</div>
                </div>
            </div>
        </div>

        <div class="config-sidebar">
            <div class="config-section">
                <label class="config-label">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Language
                </label>
                <div class="language-grid" id="languageGrid">
                    <button class="lang-btn active" data-lang="javascript">JS</button>
                    <button class="lang-btn" data-lang="typescript">TS</button>
                    <button class="lang-btn" data-lang="python">Py</button>
                    <button class="lang-btn" data-lang="cpp">C++</button>
                    <button class="lang-btn" data-lang="java">Java</button>
                    <button class="lang-btn" data-lang="php">PHP</button>
                    <button class="lang-btn" data-lang="csharp">C#</button>
                    <button class="lang-btn" data-lang="go">Go</button>
                    <button class="lang-btn" data-lang="rust">Rust</button>
                    <button class="lang-btn" data-lang="ruby">Ruby</button>
                    <button class="lang-btn" data-lang="swift">Swift</button>
                </div>
            </div>

            <div class="config-section">
                <label class="config-label">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    Humanize Intensity
                </label>
                <div class="intensity-control">
                    <input type="range" id="intensitySlider" min="10" max="100" value="85" class="intensity-slider">
                    <div class="intensity-labels">
                        <span>Light</span>
                        <span id="intensityValue">65%</span>
                        <span>Aggressive</span>
                    </div>
                    <div class="intensity-tags">
                        <span class="tag">Comments</span>
                        <span class="tag">Debug</span>
                        <span class="tag">Style</span>
                        <span class="tag" id="extraTag">Whitespace</span>
                    </div>
                </div>
            </div>

            <div class="config-section">
                <label class="config-label">Techniques Applied</label>
                <div class="techniques-list" id="techniquesList">
                    <div class="tech-item"><span class="tech-dot"></span>Human-like Comments</div>
                    <div class="tech-item"><span class="tech-dot"></span>Debug Statements</div>
                    <div class="tech-item"><span class="tech-dot"></span>Style Variation</div>
                    <div class="tech-item"><span class="tech-dot"></span>Whitespace Naturalization</div>
                    <div class="tech-item"><span class="tech-dot"></span>Comment-out Artifacts</div>
                    <div class="tech-item"><span class="tech-dot"></span>Quote Inconsistency</div>
                </div>
            </div>

            <div style="display:flex;gap:0.5rem;align-items:stretch">
                <button class="btn btn-primary" id="humanizeBtn" style="flex:1">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>Humanize Code</span>
                </button>
                <div id="aiBadge" class="ai-badge hidden" style="display:none;align-self:center">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    AI
                </div>
            </div>

            <div class="stats-box" id="statsBox" style="display:none;">
                <div class="stats-row">
                    <span>Changes</span>
                    <span id="statChanges">0</span>
                </div>
                <div class="stats-row">
                    <span>Lines</span>
                    <span id="statLines">0 → 0</span>
                </div>
                <div class="stats-row">
                    <span>Time</span>
                    <span id="statTime">0ms</span>
                </div>
            </div>
        </div>
    </div>

    <div class="features-section">
        <div class="feature-card">
            <div class="feature-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <h3>Bypass Detectors</h3>
            <p>Advanced techniques that fool GPTZero, Originality.ai, Codeleak, and more</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
            </div>
            <h3>Multi-Language</h3>
            <p>Support for JavaScript, Python, C++, Java, PHP, Rust, Go, and more</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            </div>
            <h3>Configurable</h3>
            <p>Adjust intensity from light to aggressive, fine-tune the humanization level</p>
        </div>
    </div>
</main>

<div class="toast" id="toast"></div>

<div class="modal-overlay hidden" id="settingsModal">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h2>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                AI Engine Configuration
            </h2>
            <button class="modal-close" id="modalClose">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-toggle">
                    <span>Enable AI Enhancement</span>
                    <label class="switch">
                        <input type="checkbox" id="aiEnhanceToggle">
                        <span class="slider"></span>
                    </label>
                </label>
                <p class="form-hint">When enabled, code passes through an AI model for advanced humanization after rule-based processing</p>
            </div>

            <div class="provider-categories" id="providerCategories">
                <div class="provider-category">
                    <div class="category-header">
                        <span class="category-badge free">Zero Config</span>
                        <span class="category-desc">No API key needed — works out of the box</span>
                    </div>
                    <div class="provider-grid" data-category="zero">
                        <div class="provider-card" data-provider="pollinations">
                            <div class="provider-name">Pollinations</div>
                            <div class="provider-models">
                                <select class="model-select" data-provider="pollinations">
                                    <option value="openai">openai (default)</option>
                                    <option value="openai-large">openai-large</option>
                                    <option value="mistral">mistral</option>
                                    <option value="llama">llama</option>
                                </select>
                            </div>
                        </div>
                        <div class="provider-card" data-provider="puter">
                            <div class="provider-name">Puter.js</div>
                            <div class="provider-desc">Browser-side only</div>
                        </div>
                    </div>
                </div>

                <div class="provider-category">
                    <div class="category-header">
                        <span class="category-badge free">Free Tier</span>
                        <span class="category-desc">Free API available — key optional for higher limits</span>
                    </div>
                    <div class="provider-grid" data-category="freetier">
                        <div class="provider-card" data-provider="groq">
                            <div class="provider-name">Groq</div>
                            <div class="provider-key-row">
                                <input type="password" class="provider-key" data-key="groq_key" placeholder="gsk_... (optional)">
                                <button class="key-toggle" data-target="groq_key" tabindex="-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            <div class="provider-models">
                                <select class="model-select" data-provider="groq">
                                    <option value="llama-3.3-70b-versatile">Llama 3.3 70B</option>
                                    <option value="llama-3.1-8b-instant">Llama 3.1 8B</option>
                                    <option value="mixtral-8x7b-32768">Mixtral 8x7B</option>
                                </select>
                            </div>
                        </div>
                        <div class="provider-card" data-provider="together">
                            <div class="provider-name">Together AI</div>
                            <div class="provider-key-row">
                                <input type="password" class="provider-key" data-key="together_key" placeholder="Key (optional)">
                                <button class="key-toggle" data-target="together_key" tabindex="-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            <div class="provider-models">
                                <select class="model-select" data-provider="together">
                                    <option value="meta-llama/Meta-Llama-3.1-8B-Instruct-Turbo">Llama 3.1 8B</option>
                                    <option value="meta-llama/Meta-Llama-3.1-70B-Instruct-Turbo">Llama 3.1 70B</option>
                                    <option value="mistralai/Mixtral-8x7B-Instruct-v0.1">Mixtral 8x7B</option>
                                </select>
                            </div>
                        </div>
                        <div class="provider-card" data-provider="deepinfra">
                            <div class="provider-name">DeepInfra</div>
                            <div class="provider-key-row">
                                <input type="password" class="provider-key" data-key="deepinfra_key" placeholder="Key (optional)">
                                <button class="key-toggle" data-target="deepinfra_key" tabindex="-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            <div class="provider-models">
                                <select class="model-select" data-provider="deepinfra">
                                    <option value="meta-llama/Meta-Llama-3.1-8B-Instruct">Llama 3.1 8B</option>
                                    <option value="meta-llama/Meta-Llama-3.1-70B-Instruct">Llama 3.1 70B</option>
                                </select>
                            </div>
                        </div>
                        <div class="provider-card" data-provider="openrouter">
                            <div class="provider-name">OpenRouter</div>
                            <div class="provider-key-row">
                                <input type="password" class="provider-key" data-key="openrouter_key" placeholder="Key (optional)">
                                <button class="key-toggle" data-target="openrouter_key" tabindex="-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            <div class="provider-models">
                                <select class="model-select" data-provider="openrouter">
                                    <option value="meta-llama/llama-3.1-8b-instruct:free">Llama 3.1 8B (free)</option>
                                    <option value="mistralai/mistral-small-3.1-24b-instruct:free">Mistral Small (free)</option>
                                    <option value="google/gemini-2.0-flash-exp:free">Gemini 2.0 Flash (free)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="provider-category">
                    <div class="category-header">
                        <span class="category-badge premium">Premium</span>
                        <span class="category-desc">API key required</span>
                    </div>
                    <div class="provider-grid" data-category="premium">
                        <div class="provider-card" data-provider="openai">
                            <div class="provider-name">OpenAI</div>
                            <div class="provider-key-row">
                                <input type="password" class="provider-key" data-key="openai_key" placeholder="sk-...">
                                <button class="key-toggle" data-target="openai_key" tabindex="-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            <div class="provider-models">
                                <select class="model-select" data-provider="openai">
                                    <option value="gpt-4o">GPT-4o</option>
                                    <option value="gpt-4o-mini">GPT-4o Mini</option>
                                    <option value="gpt-4-turbo">GPT-4 Turbo</option>
                                    <option value="o3-mini">o3 Mini</option>
                                </select>
                            </div>
                        </div>
                        <div class="provider-card" data-provider="anthropic">
                            <div class="provider-name">Anthropic (Claude)</div>
                            <div class="provider-key-row">
                                <input type="password" class="provider-key" data-key="anthropic_key" placeholder="sk-ant-...">
                                <button class="key-toggle" data-target="anthropic_key" tabindex="-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            <div class="provider-models">
                                <select class="model-select" data-provider="anthropic">
                                    <option value="claude-sonnet-4-20250514">Claude Sonnet 4</option>
                                    <option value="claude-3-5-sonnet-20241022">Claude 3.5 Sonnet</option>
                                    <option value="claude-3-5-haiku-20241022">Claude 3.5 Haiku</option>
                                </select>
                            </div>
                        </div>
                        <div class="provider-card" data-provider="gemini">
                            <div class="provider-name">Gemini</div>
                            <div class="provider-key-row">
                                <input type="password" class="provider-key" data-key="gemini_key" placeholder="AIza...">
                                <button class="key-toggle" data-target="gemini_key" tabindex="-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            <div class="provider-models">
                                <select class="model-select" data-provider="gemini">
                                    <option value="gemini-2.0-flash">Gemini 2.0 Flash</option>
                                    <option value="gemini-2.0-pro-exp">Gemini 2.0 Pro</option>
                                    <option value="gemini-1.5-flash">Gemini 1.5 Flash</option>
                                    <option value="gemini-1.5-pro">Gemini 1.5 Pro</option>
                                </select>
                            </div>
                        </div>
                        <div class="provider-card" data-provider="deepseek">
                            <div class="provider-name">DeepSeek</div>
                            <div class="provider-key-row">
                                <input type="password" class="provider-key" data-key="deepseek_key" placeholder="sk-...">
                                <button class="key-toggle" data-target="deepseek_key" tabindex="-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            <div class="provider-models">
                                <select class="model-select" data-provider="deepseek">
                                    <option value="deepseek-chat">DeepSeek Chat (V3)</option>
                                    <option value="deepseek-reasoner">DeepSeek Reasoner (R1)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">System Prompt</label>
                <textarea id="aiSystemPrompt" class="form-input form-textarea" placeholder="Optional: Custom instructions for the AI (e.g. 'Use informal tone, add lots of debug logs')" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">AI Temperature <span id="tempValue">0.7</span></label>
                <input type="range" id="aiTemperature" min="0" max="2" step="0.1" value="0.7" class="intensity-slider">
                <div class="intensity-labels">
                    <span>Precise (0)</span>
                    <span>Creative (2)</span>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group form-group-half">
                    <label class="form-label">Max Tokens <span id="maxTokensValue">200</span></label>
                    <input type="range" id="aiMaxTokens" min="50" max="2000" step="50" value="200" class="intensity-slider">
                    <div class="intensity-labels">
                        <span>50</span>
                        <span>2000</span>
                    </div>
                </div>
                <div class="form-group form-group-half">
                    <label class="form-label">Total Token Budget</label>
                    <input type="number" id="aiTokenBudget" class="form-input" placeholder="Unlimited" min="0" step="1000" value="">
                    <p class="form-hint">Max total tokens across all requests. 0 or empty = unlimited.</p>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-sm" id="cancelSettings">Cancel</button>
            <button class="btn btn-primary" id="saveSettings" style="padding:0.5rem 1.2rem;font-size:0.82rem">Save Configuration</button>
        </div>
    </div>
</div>

<script src="js/app.js"></script>
</body>
</html>
