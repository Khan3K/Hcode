(function() {
    'use strict';

    const codeInput = document.getElementById('codeInput');
    const codeOutput = document.getElementById('codeOutput');
    const lineNumbers = document.getElementById('lineNumbers');
    const outputLineNumbers = document.getElementById('outputLineNumbers');
    const humanizeBtn = document.getElementById('humanizeBtn');
    const intensitySlider = document.getElementById('intensitySlider');
    const intensityValue = document.getElementById('intensityValue');
    const extraTag = document.getElementById('extraTag');
    const languageGrid = document.getElementById('languageGrid');
    const statsBox = document.getElementById('statsBox');
    const statChanges = document.getElementById('statChanges');
    const statLines = document.getElementById('statLines');
    const statTime = document.getElementById('statTime');
    const toast = document.getElementById('toast');
    const statusBadge = document.getElementById('statusBadge');
    const statusDot = document.querySelector('.status-dot');
    const diffContainer = document.getElementById('diffContainer');
    const outputActions = document.getElementById('outputActions');
    const copyBtn = document.getElementById('copyResult');
    const downloadBtn = document.getElementById('downloadResult');
    const loadSampleBtn = document.getElementById('loadSample');
    const clearBtn = document.getElementById('clearCode');
    const themeToggle = document.getElementById('themeToggle');

    let selectedLanguage = 'javascript';
    let isProcessing = false;
    let currentResult = '';
    let toastTimeout = null;
    let aiConfig = { ai_enhance: true };

    const samples = {
        javascript: `// Bubble Sort implementation
function bubbleSort(arr) {
    const n = arr.length;
    for (let i = 0; i < n - 1; i++) {
        for (let j = 0; j < n - i - 1; j++) {
            if (arr[j] > arr[j + 1]) {
                const temp = arr[j];
                arr[j] = arr[j + 1];
                arr[j + 1] = temp;
            }
        }
    }
    return arr;
}

const data = [64, 34, 25, 12, 22, 11, 90];
const sorted = bubbleSort(data);
console.log("Sorted array:", sorted);`,
        python: `def fibonacci(n):
    """Generate fibonacci sequence up to n terms."""
    fib_sequence = []
    a, b = 0, 1
    for _ in range(n):
        fib_sequence.append(a)
        a, b = b, a + b
    return fib_sequence

def main():
    n = 10
    result = fibonacci(n)
    print(f"Fibonacci sequence ({n} terms): {result}")

if __name__ == "__main__":
    main()`,
        cpp: `#include <iostream>
#include <vector>
#include <algorithm>

template<typename T>
T findMax(const std::vector<T>& arr) {
    if (arr.empty()) {
        throw std::runtime_error("Array is empty");
    }
    T maxVal = arr[0];
    for (size_t i = 1; i < arr.size(); i++) {
        if (arr[i] > maxVal) {
            maxVal = arr[i];
        }
    }
    return maxVal;
}

int main() {
    std::vector<int> numbers = {3, 7, 1, 9, 4, 6};
    std::cout << "Max value: " << findMax(numbers) << std::endl;
    return 0;
}`,
        php: `<?php
class UserRepository {
    private PDO $connection;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    public function findById(int $id): ?array {
        $stmt = $this->connection->prepare(
            "SELECT * FROM users WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->connection->prepare(
            "INSERT INTO users (name, email) VALUES (:name, :email)"
        );
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email']
        ]);
        return (int) $this->connection->lastInsertId();
    }
}
`,
        typescript: `interface Task {
    id: string;
    title: string;
    completed: boolean;
    createdAt: Date;
}

class TaskManager {
    private tasks: Task[] = [];

    addTask(title: string): Task {
        const task: Task = {
            id: crypto.randomUUID(),
            title,
            completed: false,
            createdAt: new Date()
        };
        this.tasks.push(task);
        return task;
    }

    completeTask(id: string): boolean {
        const task = this.tasks.find(t => t.id === id);
        if (task) {
            task.completed = true;
            return true;
        }
        return false;
    }

    getPendingTasks(): Task[] {
        return this.tasks.filter(t => !t.completed);
    }
}
`,
        java: `import java.util.*;

public class Graph {
    private Map<Integer, List<Integer>> adjList;
    
    public Graph() {
        this.adjList = new HashMap<>();
    }
    
    public void addEdge(int src, int dest) {
        adjList.computeIfAbsent(src, k -> new ArrayList<>()).add(dest);
        adjList.computeIfAbsent(dest, k -> new ArrayList<>()).add(src);
    }
    
    public List<Integer> bfs(int start) {
        List<Integer> result = new ArrayList<>();
        Set<Integer> visited = new HashSet<>();
        Queue<Integer> queue = new LinkedList<>();
        
        queue.add(start);
        visited.add(start);
        
        while (!queue.isEmpty()) {
            int node = queue.poll();
            result.add(node);
            
            for (int neighbor : adjList.getOrDefault(node, new ArrayList<>())) {
                if (!visited.contains(neighbor)) {
                    visited.add(neighbor);
                    queue.add(neighbor);
                }
            }
        }
        
        return result;
    }
}`,
        csharp: `using System;
using System.Collections.Generic;

public class Stack<T> {
    private List<T> items = new List<T>();

    public void Push(T item) {
        items.Add(item);
    }

    public T Pop() {
        if (items.Count == 0)
            throw new InvalidOperationException("Empty stack");
        T top = items[items.Count - 1];
        items.RemoveAt(items.Count - 1);
        return top;
    }

    public T Peek() {
        if (items.Count == 0)
            throw new InvalidOperationException("Empty stack");
        return items[items.Count - 1];
    }

    public int Count => items.Count;
}`,
        go: `package main

import "fmt"

func main() {
    numbers := []int{3, 7, 1, 9, 4, 6}
    max := findMax(numbers)
    fmt.Println("Max:", max)
}

func findMax(arr []int) int {
    if len(arr) == 0 {
        return 0
    }
    maxVal := arr[0]
    for i := 1; i < len(arr); i++ {
        if arr[i] > maxVal {
            maxVal = arr[i]
        }
    }
    return maxVal
}`,
        rust: `fn main() {
    let numbers = vec![3, 7, 1, 9, 4, 6];
    match find_max(&numbers) {
        Some(max) => println!("Max: {}", max),
        None => println!("Empty list"),
    }
}

fn find_max(arr: &[i32]) -> Option<i32> {
    if arr.is_empty() {
        return None;
    }
    let mut max_val = arr[0];
    for &num in arr.iter() {
        if num > max_val {
            max_val = num;
        }
    }
    Some(max_val)
}`,
        ruby: `class TaskManager
    def initialize
        @tasks = []
    end

    def add_task(title)
        task = { id: @tasks.length + 1, title: title, completed: false }
        @tasks << task
        task
    end

    def complete_task(id)
        task = @tasks.find { |t| t[:id] == id }
        task[:completed] = true if task
        task
    end

    def pending_tasks
        @tasks.select { |t| !t[:completed] }
    end

    def all_tasks
        @tasks
    end
end

manager = TaskManager.new
manager.add_task("Learn Ruby")
manager.add_task("Build something")
puts manager.pending_tasks.inspect`,
        swift: `struct Stack<T> {
    private var items: [T] = []
    
    mutating func push(_ item: T) {
        items.append(item)
    }
    
    mutating func pop() -> T? {
        guard !items.isEmpty else { return nil }
        return items.removeLast()
    }
    
    func peek() -> T? {
        items.last
    }
    
    var count: Int { items.count }
}

var stack = Stack<Int>()
stack.push(1)
stack.push(2)
stack.push(3)
print("Popped: \\(stack.pop() ?? 0)")`
    };

    const languageMap = {
        'javascript': 'JavaScript',
        'typescript': 'TypeScript',
        'python': 'Python',
        'cpp': 'C++',
        'java': 'Java',
        'php': 'PHP',
        'csharp': 'C#',
        'go': 'Go',
        'rust': 'Rust',
        'ruby': 'Ruby',
        'swift': 'Swift'
    };

    const intensityTechniques = {
        10: ['comments'],
        20: ['comments', 'whitespace'],
        30: ['comments', 'whitespace', 'debug'],
        40: ['comments', 'whitespace', 'debug', 'formatting'],
        50: ['comments', 'debug', 'whitespace', 'formatting', 'quotes'],
        60: ['comments', 'debug', 'whitespace', 'formatting', 'quotes', 'commentout'],
        70: ['comments', 'debug', 'whitespace', 'formatting', 'quotes', 'commentout', 'naming'],
        80: ['comments', 'debug', 'whitespace', 'formatting', 'quotes', 'commentout', 'naming', 'trailing'],
        90: ['comments', 'debug', 'whitespace', 'formatting', 'quotes', 'commentout', 'naming', 'trailing', 'brackets'],
        100: ['comments', 'debug', 'whitespace', 'formatting', 'quotes', 'commentout', 'naming', 'trailing', 'brackets', 'parentheses']
    };

    function updateLineNumbers(textarea, element) {
        const lines = textarea.value.split('\n').length;
        const nums = Array.from({length: lines}, (_, i) => i + 1).join('\n');
        element.textContent = nums;
    }

    codeInput.addEventListener('input', function() {
        updateLineNumbers(this, lineNumbers);
    });

    codeInput.addEventListener('scroll', function() {
        lineNumbers.scrollTop = this.scrollTop;
    });

    function initLineNumbers() {
        updateLineNumbers(codeInput, lineNumbers);
        updateLineNumbers(codeOutput, outputLineNumbers);
    }

    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            document.querySelectorAll('.tab-content').forEach(tc => tc.classList.add('hidden'));
            const target = document.getElementById('tab-' + this.dataset.tab);
            if (target) target.classList.remove('hidden');

            if (this.dataset.tab === 'diff' && currentResult) {
                renderDiff();
            }

            if (this.dataset.tab === 'output') {
                if (currentResult) {
                    outputActions.style.display = 'flex';
                }
            }
        });
    });

    languageGrid.addEventListener('click', function(e) {
        const btn = e.target.closest('.lang-btn');
        if (!btn) return;

        document.querySelectorAll('.lang-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        selectedLanguage = btn.dataset.lang;
    });

    intensitySlider.addEventListener('input', function() {
        const val = this.value;
        intensityValue.textContent = val + '%';
        updateIntensityTags(parseInt(val));
    });

    function updateIntensityTags(intensity) {
        const tags = document.querySelector('.intensity-tags');
        const tagEls = tags.querySelectorAll('.tag');

        let level;
        if (intensity <= 30) level = 'Light';
        else if (intensity <= 60) level = 'Medium';
        else if (intensity <= 80) level = 'Strong';
        else level = 'Aggressive';

        extraTag.textContent = level + ' Mode';
        extraTag.style.color = intensity > 70 ? 'var(--accent)' : 'var(--text-muted)';
    }

    humanizeBtn.addEventListener('click', humanizeCode);

    async function humanizeCode() {
        const code = codeInput.value.trim();
        if (!code) {
            showToast('Please enter some code to humanize', 'error');
            codeInput.focus();
            return;
        }

        if (isProcessing) return;

        isProcessing = true;
        humanizeBtn.classList.add('loading');
        humanizeBtn.disabled = true;
        statusDot.style.background = 'var(--warning)';
        statusBadge.querySelector('span:last-child').textContent = 'Processing...';

        const endpoint = aiConfig.ai_enhance ? 'api/ai-humanize.php' : 'humanize.php';

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    code: code,
                    language: selectedLanguage,
                    intensity: parseInt(intensitySlider.value),
                    use_ai: aiConfig.ai_enhance
                })
            });

            if (!response.ok) {
                const err = await response.json();
                throw new Error(err.error || 'Request failed');
            }

            const data = await response.json();

            if (data.success) {
                currentResult = data.humanized;
                codeOutput.value = data.humanized;
                updateLineNumbers(codeOutput, outputLineNumbers);

                statChanges.textContent = data.changes;
                statLines.textContent = data.stats.original_lines + ' → ' + data.stats.humanized_lines;
                statTime.textContent = data.execution_time;
                statsBox.style.display = 'block';
                outputActions.style.display = 'flex';

                const label = data.ai_enhanced ? 'AI enhanced' : 'rules only';
                showToast('Humanized (' + label + ')! ' + data.changes + ' changes', 'success');

                statusDot.style.background = 'var(--success)';
                statusBadge.querySelector('span:last-child').textContent = 'Ready';

                const outputTab = document.querySelector('[data-tab="output"]');
                outputTab.click();
            }
        } catch (err) {
            showToast(err.message || 'An error occurred', 'error');
            statusDot.style.background = 'var(--danger)';
            statusBadge.querySelector('span:last-child').textContent = 'Error';
        } finally {
            isProcessing = false;
            humanizeBtn.classList.remove('loading');
            humanizeBtn.disabled = false;
            setTimeout(() => {
                if (!isProcessing) {
                    statusDot.style.background = 'var(--success)';
                    statusBadge.querySelector('span:last-child').textContent = 'Ready';
                }
            }, 2000);
        }
    }

    function renderDiff() {
        if (!currentResult) return;

        const original = codeInput.value;
        const humanized = currentResult;
        const origLines = original.split('\n');
        const humLines = humanized.split('\n');
        const maxLen = Math.max(origLines.length, humLines.length);

        let html = '';
        for (let i = 0; i < maxLen; i++) {
            const orig = origLines[i] || '';
            const hum = humLines[i] || '';

            if (orig === hum) {
                html += '<div class="diff-line unchanged"><span class="diff-ln">' + (i + 1) + '</span><span class="diff-sign"> </span><span class="diff-code">' + escapeHtml(orig) + '</span></div>';
            } else {
                if (orig) {
                    html += '<div class="diff-line removed"><span class="diff-ln">' + (i + 1) + '</span><span class="diff-sign">−</span><span class="diff-code">' + escapeHtml(orig) + '</span></div>';
                }
                if (hum) {
                    html += '<div class="diff-line added"><span class="diff-ln">' + (i + 1) + '</span><span class="diff-sign">+</span><span class="diff-code">' + escapeHtml(hum) + '</span></div>';
                }
            }
        }

        diffContainer.innerHTML = html;
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    copyBtn.addEventListener('click', function() {
        if (!currentResult) return;
        navigator.clipboard.writeText(currentResult).then(() => {
            showToast('Copied to clipboard!', 'success');
        }).catch(() => {
            codeOutput.select();
            document.execCommand('copy');
            showToast('Copied!', 'success');
        });
    });

    downloadBtn.addEventListener('click', function() {
        if (!currentResult) return;
        const ext = selectedLanguage === 'python' ? 'py'
            : selectedLanguage === 'javascript' ? 'js'
            : selectedLanguage === 'typescript' ? 'ts'
            : selectedLanguage === 'cpp' ? 'cpp'
            : selectedLanguage === 'java' ? 'java'
            : selectedLanguage === 'php' ? 'php'
            : selectedLanguage === 'csharp' ? 'cs'
            : selectedLanguage === 'go' ? 'go'
            : selectedLanguage === 'rust' ? 'rs'
            : selectedLanguage === 'ruby' ? 'rb'
            : selectedLanguage === 'swift' ? 'swift' : 'txt';
        const blob = new Blob([currentResult], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'humanized.' + ext;
        a.click();
        URL.revokeObjectURL(url);
        showToast('Downloaded!', 'success');
    });

    loadSampleBtn.addEventListener('click', function() {
        const sample = samples[selectedLanguage] || samples.javascript;
        codeInput.value = sample;
        updateLineNumbers(codeInput, lineNumbers);
        showToast('Sample loaded for ' + languageMap[selectedLanguage], 'success');
    });

    clearBtn.addEventListener('click', function() {
        codeInput.value = '';
        codeOutput.value = '';
        currentResult = '';
        statsBox.style.display = 'none';
        outputActions.style.display = 'none';
        updateLineNumbers(codeInput, lineNumbers);
        updateLineNumbers(codeOutput, outputLineNumbers);
        diffContainer.innerHTML = '<div class="diff-empty">Humanize code to see the diff view</div>';
        codeInput.focus();
    });

    function showToast(message, type) {
        if (toastTimeout) clearTimeout(toastTimeout);
        toast.textContent = message;
        toast.className = 'toast' + (type ? ' ' + type : '');
        toast.classList.add('show');
        toastTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    themeToggle.addEventListener('click', function() {
        const html = document.documentElement;
        const current = html.getAttribute('data-theme');
        const next = current === 'light' ? 'dark' : 'light';
        html.setAttribute('data-theme', next);
        localStorage.setItem('hcode-theme', next);
    });

    const savedTheme = localStorage.getItem('hcode-theme');
    if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
    }

    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            humanizeCode();
        }
    });

    initLineNumbers();
    updateIntensityTags(85);

    // === SETTINGS MODAL ===
    const settingsBtn = document.getElementById('settingsBtn');
    const settingsModal = document.getElementById('settingsModal');
    const modalClose = document.getElementById('modalClose');
    const cancelSettings = document.getElementById('cancelSettings');
    const saveSettings = document.getElementById('saveSettings');
    const aiEnhanceToggle = document.getElementById('aiEnhanceToggle');
    const aiTemperature = document.getElementById('aiTemperature');
    const tempValue = document.getElementById('tempValue');
    const aiSystemPrompt = document.getElementById('aiSystemPrompt');
    const aiMaxTokens = document.getElementById('aiMaxTokens');
    const maxTokensValue = document.getElementById('maxTokensValue');
    const aiTokenBudget = document.getElementById('aiTokenBudget');

    const providerKeyMap = {
        openai: 'openai_key', anthropic: 'anthropic_key', gemini: 'gemini_key',
        deepseek: 'deepseek_key', groq: 'groq_key', together: 'together_key',
        deepinfra: 'deepinfra_key', openrouter: 'openrouter_key',
    };

    const providerModelMap = {
        openai: 'openai_model', anthropic: 'anthropic_model', gemini: 'gemini_model',
        deepseek: 'deepseek_model', groq: 'groq_model', together: 'together_model',
        deepinfra: 'deepinfra_model', openrouter: 'openrouter_model', pollinations: 'pollinations_model',
    };

    settingsBtn.addEventListener('click', function() {
        loadSettings();
        settingsModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    function closeModal() {
        settingsModal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    modalClose.addEventListener('click', closeModal);
    cancelSettings.addEventListener('click', closeModal);
    settingsModal.addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    aiTemperature.addEventListener('input', function() {
        tempValue.textContent = this.value;
    });

    aiMaxTokens.addEventListener('input', function() {
        maxTokensValue.textContent = this.value;
    });

    // Provider card selection
    document.querySelectorAll('.provider-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('.provider-key-row') || e.target.closest('.model-select')) return;
            document.querySelectorAll('.provider-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Key visibility toggles (delegated)
    document.getElementById('providerCategories').addEventListener('click', function(e) {
        const toggle = e.target.closest('.key-toggle');
        if (!toggle) return;
        const input = toggle.parentElement.querySelector('.provider-key');
        if (input) {
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    });

    async function loadSettings() {
        try {
            const resp = await fetch('api/config.php');
            const data = await resp.json();
            if (data.success) {
                aiEnhanceToggle.checked = data.ai_enhance || false;
                aiSystemPrompt.value = data.system_prompt || '';
                aiTemperature.value = data.ai_temperature || 0.7;
                tempValue.textContent = aiTemperature.value;
                aiMaxTokens.value = data.max_tokens || 200;
                maxTokensValue.textContent = aiMaxTokens.value;
                aiTokenBudget.value = data.token_budget || '';

                // Populate keys
                Object.keys(providerKeyMap).forEach(prov => {
                    const keyField = providerKeyMap[prov];
                    const input = document.querySelector(`.provider-key[data-key="${keyField}"]`);
                    if (input) input.value = data[keyField] || '';
                });

                // Populate models
                Object.keys(providerModelMap).forEach(prov => {
                    const modelField = providerModelMap[prov];
                    const select = document.querySelector(`.model-select[data-provider="${prov}"]`);
                    if (select) select.value = data[modelField] || select.options[0].value;
                });

                // Select active provider
                const activeProvider = data.provider || 'pollinations';
                const activeCard = document.querySelector(`.provider-card[data-provider="${activeProvider}"]`);
                if (activeCard) activeCard.classList.add('active');
            }
        } catch (e) {
            // defaults
        }
    }

    saveSettings.addEventListener('click', async function() {
        const activeCard = document.querySelector('.provider-card.active');
        const provider = activeCard ? activeCard.dataset.provider : 'pollinations';

        const payload = {
            provider: provider,
            ai_enhance: aiEnhanceToggle.checked,
            ai_temperature: parseFloat(aiTemperature.value),
            system_prompt: aiSystemPrompt.value,
            max_tokens: parseInt(aiMaxTokens.value),
            token_budget: parseInt(aiTokenBudget.value) || 0,
        };

        // Collect keys
        Object.keys(providerKeyMap).forEach(prov => {
            const keyField = providerKeyMap[prov];
            const input = document.querySelector(`.provider-key[data-key="${keyField}"]`);
            if (input) payload[keyField] = input.value;
        });

        // Collect models
        Object.keys(providerModelMap).forEach(prov => {
            const modelField = providerModelMap[prov];
            const select = document.querySelector(`.model-select[data-provider="${prov}"]`);
            if (select) payload[modelField] = select.value;
        });

        // Also set generic 'model' field from active provider's model
        const activeSelect = document.querySelector(`.model-select[data-provider="${provider}"]`);
        if (activeSelect) payload.model = activeSelect.value;

        saveSettings.disabled = true;
        saveSettings.textContent = 'Saving...';

        try {
            const resp = await fetch('api/config.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            });
            const data = await resp.json();
            if (data.success) {
                aiConfig = payload;
                localStorage.setItem('hcode-ai-config', JSON.stringify(payload));
                updateAiBadge();
                showToast('AI Engine configuration saved', 'success');
                closeModal();
            } else {
                showToast(data.error || 'Save failed', 'error');
            }
        } catch (e) {
            showToast('Failed to save: ' + e.message, 'error');
        } finally {
            saveSettings.disabled = false;
            saveSettings.textContent = 'Save Configuration';
        }
    });

    function updateAiBadge() {
        const badge = document.getElementById('aiBadge');
        if (aiConfig.ai_enhance) {
            badge.style.display = 'inline-flex';
        } else {
            badge.style.display = 'none';
        }
    }

    const savedAi = localStorage.getItem('hcode-ai-config');
    if (savedAi) {
        try { aiConfig = JSON.parse(savedAi); } catch(e) {}
    }
    updateAiBadge();

    console.log('%c H-CODE HUMANIZER v2.0 ',
        'background: #6c5ce7; color: white; font-size: 14px; padding: 8px 16px; border-radius: 4px; font-weight: bold;');
    console.log('%c Ctrl+Enter to humanize ', 'color: #9898b8; font-size: 12px;');
})();
