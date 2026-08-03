<?php

class Humanizer {
    private array $config;
    private array $languageConfigs;
    private string $currentLangKey = 'javascript';

    public function __construct(array $config) {
        $this->config = $config;
        $this->initLanguageConfigs();
    }

    private function initLanguageConfigs(): void {
        $this->languageConfigs = [
            'javascript' => [
                'comment_single' => '//', 'comment_multi_open' => '/*', 'comment_multi_close' => '*/',
                'debug_statements' => ['console.log(', 'console.warn(', 'console.error('],
                'debug_close' => ')',
                'var_keywords' => ['let ', 'const ', 'var '],
                'function_keyword' => 'function',
                'string_delimiters' => ["'", '"', '`'],
                'bool_values' => ['true', 'false'], 'null_value' => 'null',
                'significant_whitespace' => false,
                'has_arrow_fns' => true,
            ],
            'python' => [
                'comment_single' => '#', 'comment_multi_open' => "'''", 'comment_multi_close' => "'''",
                'debug_statements' => ['print(', 'logging.debug('],
                'debug_close' => ')',
                'var_keywords' => [],
                'function_keyword' => 'def',
                'string_delimiters' => ["'", '"'],
                'bool_values' => ['True', 'False'], 'null_value' => 'None',
                'significant_whitespace' => true,
                'has_arrow_fns' => false,
            ],
            'cpp' => [
                'comment_single' => '//', 'comment_multi_open' => '/*', 'comment_multi_close' => '*/',
                'debug_statements' => ['std::cout << ', 'printf("', 'cerr << '],
                'debug_close' => '',
                'var_keywords' => ['int ', 'float ', 'double ', 'char ', 'bool ', 'string ', 'auto ', 'const '],
                'function_keyword' => '',
                'string_delimiters' => ['"', "'"],
                'bool_values' => ['true', 'false'], 'null_value' => 'nullptr',
                'significant_whitespace' => false,
                'has_arrow_fns' => false,
            ],
            'java' => [
                'comment_single' => '//', 'comment_multi_open' => '/*', 'comment_multi_close' => '*/',
                'debug_statements' => ['System.out.println(', 'System.out.print(', 'System.err.println('],
                'debug_close' => ')',
                'var_keywords' => ['int ', 'float ', 'double ', 'char ', 'boolean ', 'String ', 'long ', 'final '],
                'function_keyword' => '',
                'string_delimiters' => ['"', "'"],
                'bool_values' => ['true', 'false'], 'null_value' => 'null',
                'significant_whitespace' => false,
                'has_arrow_fns' => false,
            ],
            'php' => [
                'comment_single' => '//', 'comment_multi_open' => '/*', 'comment_multi_close' => '*/',
                'debug_statements' => ['var_dump(', 'print_r(', 'error_log('],
                'debug_close' => ')',
                'var_keywords' => [],
                'function_keyword' => 'function',
                'string_delimiters' => ["'", '"'],
                'bool_values' => ['true', 'false'], 'null_value' => 'null',
                'significant_whitespace' => false,
                'has_arrow_fns' => false,
            ],
            'csharp' => [
                'comment_single' => '//', 'comment_multi_open' => '/*', 'comment_multi_close' => '*/',
                'debug_statements' => ['Console.WriteLine(', 'Console.Write(', 'Debug.WriteLine('],
                'debug_close' => ')',
                'var_keywords' => ['int ', 'float ', 'double ', 'char ', 'bool ', 'string ', 'var ', 'const '],
                'function_keyword' => '',
                'string_delimiters' => ['"', "'"],
                'bool_values' => ['true', 'false'], 'null_value' => 'null',
                'significant_whitespace' => false,
                'has_arrow_fns' => false,
            ],
            'go' => [
                'comment_single' => '//', 'comment_multi_open' => '/*', 'comment_multi_close' => '*/',
                'debug_statements' => ['fmt.Println(', 'fmt.Printf(', 'log.Println('],
                'debug_close' => ')',
                'var_keywords' => ['var ', 'const '],
                'function_keyword' => 'func',
                'string_delimiters' => ['"', '`'],
                'bool_values' => ['true', 'false'], 'null_value' => 'nil',
                'significant_whitespace' => false,
                'has_arrow_fns' => false,
            ],
            'rust' => [
                'comment_single' => '//', 'comment_multi_open' => '/*', 'comment_multi_close' => '*/',
                'debug_statements' => ['println!("', 'eprintln!("', 'dbg!('],
                'debug_close' => '',
                'var_keywords' => ['let ', 'let mut ', 'const '],
                'function_keyword' => 'fn',
                'string_delimiters' => ['"', "'"],
                'bool_values' => ['true', 'false'], 'null_value' => 'None',
                'significant_whitespace' => false,
                'has_arrow_fns' => true,
            ],
            'ruby' => [
                'comment_single' => '#', 'comment_multi_open' => '=begin', 'comment_multi_close' => '=end',
                'debug_statements' => ['puts ', 'p ', 'warn '],
                'debug_close' => '',
                'var_keywords' => [],
                'function_keyword' => 'def',
                'string_delimiters' => ['"', "'"],
                'bool_values' => ['true', 'false'], 'null_value' => 'nil',
                'significant_whitespace' => true,
                'has_arrow_fns' => true,
            ],
            'swift' => [
                'comment_single' => '//', 'comment_multi_open' => '/*', 'comment_multi_close' => '*/',
                'debug_statements' => ['print(', 'NSLog('],
                'debug_close' => ')',
                'var_keywords' => ['var ', 'let ', 'const '],
                'function_keyword' => 'func',
                'string_delimiters' => ['"', "'"],
                'bool_values' => ['true', 'false'], 'null_value' => 'nil',
                'significant_whitespace' => false,
                'has_arrow_fns' => false,
            ],
            'typescript' => [
                'comment_single' => '//', 'comment_multi_open' => '/*', 'comment_multi_close' => '*/',
                'debug_statements' => ['console.log(', 'console.warn(', 'console.error('],
                'debug_close' => ')',
                'var_keywords' => ['let ', 'const ', 'var '],
                'function_keyword' => 'function',
                'string_delimiters' => ["'", '"', '`'],
                'bool_values' => ['true', 'false'], 'null_value' => 'null',
                'significant_whitespace' => false,
                'has_arrow_fns' => true,
            ],
        ];
    }

    public function humanize(string $code, string $language, int $intensity = 50): string {
        $this->currentLangKey = isset($this->languageConfigs[$language]) ? $language : 'javascript';
        $lang = $this->languageConfigs[$this->currentLangKey];
        $result = $code;

        $seed = crc32($code);
        mt_srand($seed);

        $techniques = $this->getTechniquesForIntensity($intensity);

        if (in_array('syntax', $techniques)) {
            $result = $this->transformSyntaxEquivalence($result, $lang, $intensity);
        }
        if (in_array('naming', $techniques)) {
            $result = $this->addNamingInconsistency($result, $lang, $intensity);
        }
        if (in_array('restructure', $techniques) && !$lang['significant_whitespace']) {
            $result = $this->restructureCode($result, $lang, $intensity);
        }
        if (in_array('comments', $techniques)) {
            $result = $this->addHumanComments($result, $lang, $intensity);
        }
        if (in_array('debug', $techniques) && !$lang['significant_whitespace']) {
            $result = $this->addDebugStatements($result, $lang, $intensity);
        }
        if (in_array('deadcode', $techniques)) {
            $result = $this->addRealisticDeadCode($result, $lang, $intensity);
        }
        if (in_array('micro', $techniques)) {
            $result = $this->applyMicroHumanPatterns($result, $lang, $intensity);
        }
        if (in_array('whitespace', $techniques)) {
            $result = $this->addWhitespaceVariation($result, $intensity);
        }
        if (in_array('formatting', $techniques) && !$lang['significant_whitespace']) {
            $result = $this->addFormattingVariation($result, $lang, $intensity);
        }
        if (in_array('quotes', $techniques)) {
            $result = $this->mixStringQuotes($result, $lang, $intensity);
        }
        if (in_array('commentout', $techniques)) {
            $result = $this->addCommentedOutCode($result, $lang, $intensity);
        }
        if (in_array('trailing', $techniques)) {
            $result = $this->addTrailingWhitespace($result, $intensity);
        }
        if (in_array('brackets', $techniques) && !$lang['significant_whitespace']) {
            $result = $this->addBracketStyleVariation($result, $intensity);
        }

        return $result;
    }

    private function getTechniquesForIntensity(int $intensity): array {
        $all = ['syntax', 'comments', 'debug', 'whitespace', 'quotes', 'formatting',
                'commentout', 'trailing', 'brackets', 'deadcode', 'naming', 'restructure', 'micro'];

        if ($intensity <= 20) {
            $count = 4;
        } elseif ($intensity <= 40) {
            $count = 6;
        } elseif ($intensity <= 60) {
            $count = 8;
        } elseif ($intensity <= 80) {
            $count = 10;
        } else {
            $count = count($all);
        }

        $shuffled = $all;
        shuffle($shuffled);
        return array_slice($shuffled, 0, $count);
    }

    private function isLineComment(string $line, string $commentSingle): bool {
        $trimmed = trim($line);
        return str_starts_with($trimmed, $commentSingle)
            || str_starts_with($trimmed, '/*')
            || str_starts_with($trimmed, '*')
            || str_starts_with($trimmed, '#');
    }

    // === SYNTAX EQUIVALENCE TRANSFORMATIONS ===
    private function transformSyntaxEquivalence(string $code, array $lang, int $intensity): string {
        $langKey = $this->currentLangKey;

        switch ($langKey) {
            case 'javascript':
            case 'typescript':
                $code = $this->transformJSTernaryToIf($code, $intensity);
                $code = $this->transformJSForEachToFor($code, $intensity);
                $code = $this->transformJSLetConstSwap($code, $intensity);
                $code = $this->transformJSTemplateToConcat($code, $intensity);
                break;
            case 'python':
                $code = $this->transformPyListCompToLoop($code, $intensity);
                break;
            case 'php':
                $code = $this->transformPhpArrayMapToForeach($code, $intensity);
                break;
        }

        return $code;
    }

    private function transformJSTernaryToIf(string $code, int $intensity): string {
        $chance = $intensity / 200;
        if (mt_rand(1, 100) > $chance * 100) return $code;

        return preg_replace_callback(
            '/(?:const|let|var)\s+(\w+)\s*=\s*(.+?)\s*\?\s*(.+?)\s*:\s*(.+?);$/m',
            function($m) use ($intensity) {
                $var = $m[1];
                $cond = trim($m[2]);
                $thenVal = trim($m[3]);
                $elseVal = trim($m[4]);
                $indent = $this->getIndent($m[0]);

                if (str_contains($thenVal, '(') && !str_contains($thenVal, ')')) return $m[0];
                if (str_contains($elseVal, '(') && !str_contains($elseVal, ')')) return $m[0];

                return "{$indent}{$var};\n{$indent}if ({$cond}) {\n{$indent}    {$var} = {$thenVal};\n{$indent}} else {\n{$indent}    {$var} = {$elseVal};\n{$indent}}";
            },
            $code,
            mt_rand(1, 3)
        );
    }

    private function transformJSForEachToFor(string $code, int $intensity): string {
        $chance = $intensity / 300;
        if (mt_rand(1, 100) > $chance * 100) return $code;

        return preg_replace_callback(
            '/(\w+)\.forEach\s*\(\s*(?:function\s*)?\s*\(?(\w+)\)?\s*(?:=>)?\s*\{/m',
            function($m) {
                $arr = $m[1];
                $item = $m[2];
                $indent = '';
                preg_match('/^(\s*)/', $m[0], $im);
                $indent = $im[1] ?? '';
                $replacement = "for (const {$item} of {$arr}) {";
                if (mt_rand(0, 1)) {
                    $idx = $item . 'Idx';
                    $replacement = "for (let {$idx} = 0; {$idx} < {$arr}.length; {$idx}++) {\n{$indent}    const {$item} = {$arr}[{$idx}];";
                }
                $rest = substr($m[0], strlen($m[1]) + 9 + strlen($m[2]) + strpos($m[0], '(' . $m[2] . ')') - strpos($m[0], '.forEach'));
                return $indent . $replacement;
            },
            $code,
            1
        );
    }

    private function transformJSLetConstSwap(string $code, int $intensity): string {
        $chance = $intensity / 150;
        $result = preg_replace_callback(
            '/\b(let|const|var)\s+(\w+)\s*=/m',
            function($m) use ($chance) {
                if (mt_rand(1, 100) > $chance * 100) return $m[0];
                $keyword = $m[1];
                $swap = ['let' => 'const', 'const' => 'let', 'var' => 'let'];
                if (isset($swap[$keyword]) && mt_rand(0, 1)) {
                    return $swap[$keyword] . ' ' . $m[2] . ' =';
                }
                return $m[0];
            },
            $code
        );
        return $result ?: $code;
    }

    private function transformJSTemplateToConcat(string $code, int $intensity): string {
        $chance = $intensity / 250;
        if (mt_rand(1, 100) > $chance * 100) return $code;

        return preg_replace_callback(
            '/`([^`]*(?:\$\{[^}]+}[^`]*)*)`/',
            function($m) {
                $inner = $m[1];
                if (preg_match_all('/\$\{(\w+)\}/', $inner, $vars)) {
                    $parts = preg_split('/\$\{\w+\}/', $inner);
                    $result = '';
                    foreach ($parts as $i => $part) {
                        if ($i > 0) {
                            $result .= '" + ' . $vars[1][$i-1] . ' + "';
                        }
                        $result .= $part;
                    }
                    return '"' . $result . '"';
                }
                return $m[0];
            },
            $code,
            mt_rand(1, 2)
        );
    }

    private function transformPyListCompToLoop(string $code, int $intensity): string {
        $chance = $intensity / 250;
        if (mt_rand(1, 100) > $chance * 100) return $code;

        return preg_replace_callback(
            '/(\w+)\s*=\s*\[([^\]]+)\s+for\s+(\w+)\s+in\s+(\w+)\]/m',
            function($m) {
                $var = $m[1];
                $expr = trim($m[2]);
                $iter = $m[3];
                $list = $m[4];
                $indent = $this->getIndent($m[0]);

                return "{$var} = []\n{$indent}for {$iter} in {$list}:\n{$indent}    {$var}.append({$expr})";
            },
            $code,
            1
        );
    }

    private function transformPhpArrayMapToForeach(string $code, int $intensity): string {
        $chance = $intensity / 250;
        if (mt_rand(1, 100) > $chance * 100) return $code;

        return preg_replace_callback(
            '/\$(\w+)\s*=\s*array_map\s*\(\s*function\s*\(\$(\w+)\)\s*(?:use\s*\([^)]*\))?\s*\{([^}]+)\},\s*\$(\w+)\s*\)\s*;/m',
            function($m) {
                $resultVar = $m[1];
                $param = $m[2];
                $body = trim($m[3]);
                $source = $m[4];
                $indent = $this->getIndent($m[0]);

                if (!str_contains($body, 'return')) return $m[0];
                $body = trim(str_replace('return ', '', $body));

                return "{$indent}\${$resultVar} = [];\n{$indent}foreach (\${$source} as \${$param}) {\n{$indent}    \${$resultVar}[] = {$body};\n{$indent}}";
            },
            $code,
            1
        );
    }

    // === VARIABLE RENAMING OVERHAUL ===
    private function addNamingInconsistency(string $code, array $lang, int $intensity): string {
        if ($intensity < 20) return $code;

        $lines = explode("\n", $code);
        $result = [];

        $shortNames = ['x', 'y', 'z', 'i', 'j', 'k', 'n', 'm', 'tmp', 'val', 'res', 'buf', 'arr', 'str', 'num', 'idx', 'pos', 'len', 'cnt', 'sum'];
        $humanNames = ['foo', 'bar', 'baz', 'thing', 'data', 'stuff', 'item', 'elem', 'temp', 'total', 'curr', 'prev', 'next', 'last', 'src', 'dst', 'key', 'counter', 'result', 'output', 'input', 'value', 'index', 'length', 'count', 'helper', 'cache', 'lookup'];

        $longNames = ['accumulatedResult', 'temporaryStorage', 'processedData', 'currentItem', 'finalOutput', 'computedValue', 'workingCopy', 'cachedResult', 'errorMessage', 'statusCode', 'userInput', 'outputBuffer'];

        $reserved = ['if', 'else', 'for', 'while', 'do', 'switch', 'case', 'break', 'continue', 'return', 'function', 'var', 'let', 'const', 'class', 'new', 'this', 'super', 'typeof', 'instanceof', 'void', 'delete', 'import', 'export', 'default', 'from', 'async', 'await', 'yield', 'throw', 'try', 'catch', 'finally', 'in', 'of', 'static', 'extends', 'implements', 'interface', 'enum', 'package', 'private', 'protected', 'public', 'abstract', 'final', 'def', 'print', 'None', 'True', 'False', 'import', 'from', 'class', 'self', 'nil', 'true', 'false', 'null', 'nil', 'nullptr', 'undefined', 'NaN', 'int', 'float', 'double', 'char', 'bool', 'string', 'void', 'size_t', 'auto', 'include', 'namespace', 'using', 'template', 'typename', 'struct', 'virtual', 'override', 'explicit', 'mutable', 'friend'];

        $renameMap = [];
        $usedNames = [];

        foreach ($lines as $line) {
            $modified = $line;
            $trimmed = trim($line);
            if (empty($trimmed)) {
                $result[] = $line;
                continue;
            }

            $isDeclLine = preg_match('/\b(let|const|var|function|def|fn|func)\b/', $trimmed) || preg_match('/^(\s*)(\w+\s+=)/m', $trimmed);

            if (mt_rand(1, 100) <= 35 && $intensity >= 40) {
                $modified = preg_replace_callback('/\b([a-zA-Z_]\w{2,})\b/', function($m) use (&$renameMap, &$usedNames, $shortNames, $humanNames, $longNames, $reserved, $intensity) {
                    $name = $m[1];
                    if (in_array($name, $reserved)) return $m[0];
                    if (strtoupper($name) === $name) return $m[0];

                    $decider = mt_rand(1, 100);
                    $pool = $shortNames;
                    if ($decider <= 40) {
                        $pool = $humanNames;
                    } elseif ($decider <= 55 && $intensity >= 70) {
                        $pool = $longNames;
                    }

                    $newname = $pool[array_rand($pool)];

                    if ($intensity >= 60 && mt_rand(0, 1)) {
                        if (ctype_lower($name[0])) {
                            $newname = lcfirst($newname);
                        }
                    }

                    if ($intensity >= 75 && mt_rand(0, 1)) {
                        $newname = $newname . mt_rand(2, 99);
                    }

                    if (in_array($newname, $usedNames)) {
                        $newname .= mt_rand(1, 9);
                    }
                    $usedNames[] = $newname;
                    $renameMap[$name] = $newname;
                    return $newname;
                }, $modified, mt_rand(1, 2));
            }

            if (!empty($renameMap) && mt_rand(1, 100) <= 40) {
                $modified = preg_replace_callback('/\b([a-zA-Z_]\w+)\b/', function($m) use ($renameMap) {
                    if (isset($renameMap[$m[1]])) {
                        return $renameMap[$m[1]];
                    }
                    return $m[0];
                }, $modified);
            }

            $result[] = $modified;
        }

        return implode("\n", $result);
    }

    // === CODE RESTRUCTURING ===
    private function restructureCode(string $code, array $lang, int $intensity): string {
        if ($intensity < 40) return $code;

        $lines = explode("\n", $code);
        $result = $lines;

        if (mt_rand(1, 100) <= 20 && $intensity >= 50) {
            $methodBounds = $this->findMethodBounds($lines);
            if (count($methodBounds) >= 2) {
                $idx1 = array_rand($methodBounds);
                $idx2 = array_rand($methodBounds);
                if ($idx1 !== $idx2) {
                    $m1 = $methodBounds[$idx1];
                    $m2 = $methodBounds[$idx2];

                    $block1 = array_slice($lines, $m1['start'], $m1['end'] - $m1['start'] + 1);
                    $block2 = array_slice($lines, $m2['start'], $m2['end'] - $m2['start'] + 1);

                    array_splice($result, $m1['start'], $m1['end'] - $m1['start'] + 1, $block2);
                    $offset = count($block2) - ($m1['end'] - $m1['start'] + 1);
                    array_splice($result, $m2['start'] + $offset, $m2['end'] - $m2['start'] + 1, $block1);
                }
            }
        }

        if (mt_rand(1, 100) <= 15) {
            $newResult = [];
            foreach ($result as $i => $line) {
                $newResult[] = $line;
                $trimmed = trim($line);
                if (!empty($trimmed) && preg_match('/\}$/', $trimmed) && $i < count($result) - 1 && mt_rand(0, 2) === 0) {
                    $newResult[] = '';
                }
            }
            $result = $newResult;
        }

        return implode("\n", $result);
    }

    private function findMethodBounds(array $lines): array {
        $bounds = [];
        $i = 0;
        $langKey = $this->currentLangKey;
        while ($i < count($lines)) {
            $line = $lines[$i];
            $trimmed = trim($line);
            $isMethod = false;

            if ($langKey === 'javascript' || $langKey === 'typescript') {
                if (preg_match('/^\s*(async\s+)?(function|get|set)\s+\w+\s*\(/', $trimmed) ||
                    preg_match('/^\s*(\w+)\s*\([^)]*\)\s*\{/', $trimmed) ||
                    preg_match('/^\s*\w+\s*=\s*(?:async\s*)?\([^)]*\)\s*=>\s*\{/', $trimmed)) {
                    $isMethod = true;
                }
            } elseif ($langKey === 'php') {
                if (preg_match('/^\s*(public|private|protected|static|function)\s+(function\s+)?\w+\s*\(/', $trimmed)) {
                    $isMethod = true;
                }
            } elseif ($langKey === 'java' || $langKey === 'csharp') {
                if (preg_match('/^\s*(public|private|protected|static|final|abstract|synchronized)?\s*\w+\s+\w+\s*\([^)]*\)\s*(throws\s+\w+)?\s*\{/', $trimmed)) {
                    $isMethod = true;
                }
            } elseif ($langKey === 'python') {
                if (preg_match('/^\s*def\s+\w+\s*\(/', $trimmed)) {
                    $isMethod = true;
                }
            } elseif ($langKey === 'cpp') {
                if (preg_match('/^\s*\w+\s+\w+\s*\([^)]*\)\s*(const\s*)?\{/', $trimmed)) {
                    $isMethod = true;
                }
            } elseif ($langKey === 'go') {
                if (preg_match('/^\s*func\s+(\(\w+\s+\*?\w+\)\s+)?\w+\s*\(/', $trimmed)) {
                    $isMethod = true;
                }
            } elseif ($langKey === 'rust') {
                if (preg_match('/^\s*fn\s+\w+\s*\(/', $trimmed)) {
                    $isMethod = true;
                }
            }

            if ($isMethod) {
                $start = $i;
                $braceCount = 0;
                $inBlock = false;
                for ($j = $i; $j < count($lines); $j++) {
                    $l = $lines[$j];
                    $braceCount += substr_count($l, '{');
                    $braceCount -= substr_count($l, '}');
                    if ($braceCount > 0) $inBlock = true;
                    if ($inBlock && $braceCount === 0) {
                        $bounds[] = ['start' => $start, 'end' => $j];
                        $i = $j;
                        break;
                    }
                }
            }
            $i++;
        }
        return $bounds;
    }

    // === CONTEXT-AWARE COMMENTS ===
    private function addHumanComments(string $code, array $lang, int $intensity): string {
        $lines = explode("\n", $code);
        $result = [];
        $commentFreq = max(3, (int)(20 - ($intensity / 7)));

        $commentTemplates = [
            '// TODO: handle edge case in {fn}',
            '// FIXME: {fn} doesnt work with empty input',
            '// @improve: this {var} logic could be cleaner',
            '// NOTE: {fn} might break on edge cases',
            '// HACK: quick fix for {var} issue',
            '// XXX: need to revisit {fn} implementation',
            '// REVIEW: check if {var} is correct here',
            '// OPTIMIZE: {fn} is slow, refactor later',
            '// TODO: add proper error handling in {fn}',
            '// not sure why {var} works this way',
            '// TODO: refactor {fn} when we have time',
            '// BUG: off-by-one in {fn}',
            '// this {var} thing is confusing',
            '// left this here for now, need to fix',
            '// FIXME: crashes when {var} is null',
            '// TODO: make {fn} configurable',
            '// lol this is ugly but works',
            '// i think this is right?',
            '// dont touch this it works somehow',
            '// TODO: ask senior about {fn} logic',
        ];

        $identifiers = $this->extractIdentifiers($lines);

        $i = 0;
        foreach ($lines as $line) {
            $result[] = $line;
            $trimmed = trim($line);
            if (empty($trimmed)) { $i++; continue; }

            $indent = $this->getIndent($line);

            if ($this->isLineComment($line, $lang['comment_single'])) { $i++; continue; }

            if (mt_rand(1, $commentFreq) === 1 && $i < count($lines) - 1) {
                $template = $commentTemplates[array_rand($commentTemplates)];
                $fn = $identifiers['functions'] ? $identifiers['functions'][array_rand($identifiers['functions'])] : 'this';
                $var = $identifiers['variables'] ? $identifiers['variables'][array_rand($identifiers['variables'])] : 'thing';
                $comment = str_replace(['{fn}', '{var}'], [$fn, $var], $template);

                if ($lang['comment_single'] === '#') {
                    $commentText = ltrim($comment, '/ ');
                    $result[] = $indent . '# ' . $commentText;
                } else {
                    $result[] = $indent . $comment;
                }
            }
            $i++;
        }

        return implode("\n", $result);
    }

    private function extractIdentifiers(array $lines): array {
        $functions = [];
        $variables = [];
        $langKey = $this->currentLangKey;

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (preg_match('/function\s+(\w+)/', $trimmed, $m)) {
                $functions[] = $m[1];
            }
            if (preg_match('/(?:let|const|var|def|fn|func)\s+(\w+)/', $trimmed, $m)) {
                $variables[] = $m[1];
            }
            if (preg_match('/\$(\w+)\s*=/', $trimmed, $m)) {
                $variables[] = '$' . $m[1];
            }
            if (preg_match('/(?:def|fn|func)\s+(\w+)/', $trimmed, $m)) {
                $functions[] = $m[1];
            }
            if (preg_match('/->\s*(\w+)\s*\(/', $trimmed, $m)) {
                $functions[] = $m[1];
            }
        }

        if (empty($functions)) $functions = ['processData', 'handleRequest', 'computeResult', 'validateInput'];
        if (empty($variables)) $variables = ['data', 'result', 'value', 'item', 'index', 'count', 'temp', 'config', 'input', 'output'];

        return [
            'functions' => array_unique($functions),
            'variables' => array_unique($variables),
        ];
    }

    // === REALISTIC DEBUG STATEMENTS ===
    private function addDebugStatements(string $code, array $lang, int $intensity): string {
        $lines = explode("\n", $code);
        $result = [];
        $debugFreq = max(7, (int)(22 - ($intensity / 6)));

        $identifiers = $this->extractIdentifiers($lines);
        $allNames = array_merge($identifiers['functions'], $identifiers['variables']);
        $sampleName = $allNames ? $allNames[array_rand($allNames)] : 'value';

        $langKey = $this->currentLangKey;

        foreach ($lines as $i => $line) {
            $result[] = $line;
            $trimmed = trim($line);
            if (empty($trimmed)) continue;
            if ($this->isLineComment($line, $lang['comment_single'])) continue;

            $indent = $this->getIndent($line);

            $isDebug = false;
            foreach ($lang['debug_statements'] as $ds) {
                if (str_contains($trimmed, $ds)) { $isDebug = true; break; }
            }
            if ($isDebug) continue;

            if (mt_rand(1, $debugFreq) === 1 && $i < count($lines) - 2) {
                $debugStmt = $lang['debug_statements'][array_rand($lang['debug_statements'])];

                $contextMsg = '';
                if (mt_rand(0, 1)) {
                    $contextMsg = 'check ' . $sampleName . ': ';
                } else {
                    $contextMsg = 'debug ' . $sampleName . ' = ';
                }

                switch ($langKey) {
                    case 'cpp':
                        if ($debugStmt === 'printf("') {
                            $result[] = $indent . 'printf("' . $contextMsg . ' %d\\n", ' . ltrim($sampleName, '$') . ');';
                        } else {
                            $result[] = $indent . 'std::cout << "' . $contextMsg . '" << ' . ltrim($sampleName, '$') . ' << std::endl;';
                        }
                        break;
                    case 'rust':
                        $result[] = $indent . 'println!("{} {}", "' . $contextMsg . '", ' . ltrim($sampleName, '$') . ');';
                        break;
                    case 'go':
                        $result[] = $indent . 'fmt.Println("' . $contextMsg . '", ' . ltrim($sampleName, '$') . ')';
                        break;
                    case 'python':
                        $result[] = $indent . 'print(f"' . $contextMsg . '{' . ltrim($sampleName, '$') . '}")';
                        break;
                    case 'ruby':
                        $result[] = $indent . 'puts "' . $contextMsg . '#{' . ltrim($sampleName, '$') . '}"';
                        break;
                    case 'php':
                        $result[] = $indent . 'echo "' . $contextMsg . '" . ' . $sampleName . '; // dbg';
                        break;
                    default:
                        $result[] = $indent . $debugStmt . '"' . $contextMsg . '", ' . ltrim($sampleName, '$') . ');';
                        break;
                }
            }
        }

        return implode("\n", $result);
    }

    // === REALISTIC DEAD CODE (no "// unused" markers) ===
    private function addRealisticDeadCode(string $code, array $lang, int $intensity): string {
        if ($intensity < 30) return $code;

        $lines = explode("\n", $code);
        $result = [];
        $inserted = false;

        $identifiers = $this->extractIdentifiers($lines);
        $allNames = $identifiers['variables'];
        $dummyName = $allNames ? $allNames[array_rand($allNames)] : 'result';

        $deadPatterns = [
            'javascript' => [
                "// keeping this for reference\n// const oldImpl = (v) => v * 2;",
                "// @todo move this to a util later\n// const format_{name} = (val) => val.toString();",
                "let cache = new Map();\n// FIXME: memory leak here",
                "// console.log('{name}:', {name});",
            ],
            'typescript' => [
                "// keeping this for reference\n// const oldImpl = (v: any): any => v * 2;",
                "// @todo move this to a util later\n// const format_{name} = (val: any): string => val.toString();",
                "let cache = new Map<string, any>();\n// FIXME: memory leak here",
            ],
            'python' => [
                "# keeping this for reference\n# def old_impl(v): return v * 2",
                "# @todo: move to utils\n# def format_{name}(val): return str(val)",
                "_cache = {}  # TODO: add TTL",
            ],
            'php' => [
                "// keeping this for reference\n// function oldImpl($v) { return $v * 2; }",
                "// @todo move to helper\n// function format_{name}($val) { return (string)$val; }",
                "\$cache = []; // TODO: implement TTL",
            ],
        ];

        $patterns = $deadPatterns[$this->currentLangKey] ?? $deadPatterns['javascript'];

        foreach ($lines as $i => $line) {
            $result[] = $line;
            if ($inserted) continue;
            $trimmed = trim($line);
            if (empty($trimmed)) continue;

            if (mt_rand(1, 12) === 1 && $i > 1 && $i < count($lines) - 2) {
                $indent = $this->getIndent($line);
                $pattern = $patterns[array_rand($patterns)];
                $stmt = str_replace('{name}', $dummyName, $pattern);
                $stmtLines = explode("\n", $stmt);
                foreach ($stmtLines as $sl) {
                    $result[] = $indent . $sl;
                }
                $inserted = true;
            }
        }

        return implode("\n", $result);
    }

    // === MICRO-HUMAN PATTERNS ===
    private function applyMicroHumanPatterns(string $code, array $lang, int $intensity): string {
        if ($intensity < 30) return $code;

        $result = $code;

        if (mt_rand(1, 100) <= 15 + ($intensity / 10)) {
            $result = preg_replace_callback('/return\s+(.+?);/m', function($m) {
                $expr = trim($m[1]);
                if (!str_starts_with($expr, '(') && !str_contains($expr, ';')) {
                    return 'return (' . $expr . ');';
                }
                return $m[0];
            }, $result);
        }

        if (mt_rand(1, 100) <= 10 && $intensity >= 50) {
            $result = preg_replace_callback('/if\s*\(\s*(\w+)\s*\)/m', function($m) {
                if (mt_rand(0, 1)) {
                    return 'if (' . $m[1] . ' === true)';
                }
                return $m[0];
            }, $result, mt_rand(1, 2));
        }

        if (mt_rand(1, 100) <= 8 && $intensity >= 60) {
            $result = preg_replace_callback('/if\s*\(\s*(\w+)\s*===?\s*null\s*\)/m', function($m) {
                return 'if (null === ' . $m[1] . ')';
            }, $result, 1);
        }

        if (mt_rand(1, 100) <= 10 && $intensity >= 55) {
            $result = preg_replace_callback('/return\s+(.+?);/m', function($m) {
                $expr = $m[1];
                if (!str_contains($expr, '!!') && preg_match('/^\w+$/', $expr)) {
                    return 'return !!' . $expr . ';';
                }
                return $m[0];
            }, $result, 1);
        }

        if (mt_rand(1, 100) <= 12) {
            $result = preg_replace_callback('/\barray\b/', function($m) { return 'arr'; }, $result, 1);
        }

        if (mt_rand(1, 100) <= 8 && $intensity >= 65) {
            $result = preg_replace_callback('/\.length\s*===\s*0/', function($m) { return '.length === 0'; }, $result, 1);
        }

        return $result;
    }

    // === WHITESPACE VARIATION ===
    private function addWhitespaceVariation(string $code, int $intensity): string {
        $lines = explode("\n", $code);
        $result = [];
        $blankFreq = max(3, (int)(9 - ($intensity / 18)));
        $consecutiveBlanks = 0;

        foreach ($lines as $i => $line) {
            $result[] = $line;
            $trimmed = trim($line);

            if (empty($trimmed)) {
                $consecutiveBlanks++;
                continue;
            }
            $consecutiveBlanks = 0;

            if (mt_rand(1, $blankFreq) === 1 && $consecutiveBlanks < 2) {
                $extra = mt_rand(1, min(2, (int)($intensity / 40)));
                for ($j = 0; $j < $extra; $j++) {
                    $result[] = '';
                }
            }
        }

        return implode("\n", $result);
    }

    // === FORMATTING CHAOS ===
    private function addFormattingVariation(string $code, array $lang, int $intensity): string {
        $lines = explode("\n", $code);
        $result = [];
        $langKey = $this->currentLangKey;

        foreach ($lines as $i => $line) {
            $modified = $line;

            $indent = '';
            if (preg_match('/^(\s+)/', $modified, $m)) {
                $indent = $m[1];
                $modified = substr($modified, strlen($indent));
            }

            if (mt_rand(1, 100) <= 10 && $intensity >= 40) {
                if (($langKey === 'javascript' || $langKey === 'typescript')) {
                    $modified = preg_replace('/\s*([=+-\/*%<>!?&|])\s*/', ' $1 ', $modified);
                    $modified = preg_replace('/\s{2,}/', ' ', $modified);
                    $modified = preg_replace('/\s*([,;])\s*/', '$1 ', $modified);
                }
            }

            if (mt_rand(1, 100) <= 8 && $intensity >= 50) {
                $modified = preg_replace('/\s*([=])\s*/', ' = ', $modified, 1);
            }

            if (mt_rand(1, 100) <= 6 && $intensity >= 60) {
                $modified = preg_replace('/\s*,\s*/', ',', $modified, 1);
            }

            $result[] = $indent . $modified;
        }

        return implode("\n", $result);
    }

    // === COMMENTED-OUT CODE ARTIFACTS ===
    private function addCommentedOutCode(string $code, array $lang, int $intensity): string {
        if ($intensity < 40) return $code;

        $commentedPatterns = [
            'javascript' => 'console.log("checkpoint reached", data);',
            'typescript' => 'console.log("checkpoint reached", data);',
            'python' => 'print(f"checkpoint: {value}")',
            'cpp' => 'std::cout << "testing " << x << std::endl;',
            'java' => 'System.out.println("debug: " + x);',
            'php' => 'echo "debug: " . $var;',
            'csharp' => 'Console.WriteLine("debug: " + x);',
            'go' => 'fmt.Println("check here", val)',
            'rust' => 'println!("check {:?}", val);',
            'ruby' => 'puts "debug line #{x}"',
            'swift' => 'print("debug \\(x)")',
        ];

        $lines = explode("\n", $code);
        $result = [];
        $inserted = false;
        $pattern = $commentedPatterns[$this->currentLangKey] ?? $commentedPatterns['javascript'];

        foreach ($lines as $i => $line) {
            $result[] = $line;
            $trimmed = trim($line);

            if (empty($trimmed) || $inserted) continue;
            if ($this->isLineComment($line, $lang['comment_single'])) continue;

            if (mt_rand(1, 14) === 1 && $i > 1 && $i < count($lines) - 2) {
                $indent = $this->getIndent($line);
                $prefix = $lang['comment_single'];
                if ($intensity >= 70 && mt_rand(0, 1)) {
                    $result[] = $indent . $prefix . ' ' . $prefix . ' was using this for debugging';
                    $result[] = $indent . $prefix . ' ' . $pattern;
                } else {
                    $result[] = $indent . $prefix . ' ' . $pattern;
                }
                $inserted = true;
            }
        }

        return implode("\n", $result);
    }

    // === TRAILING WHITESPACE ===
    private function addTrailingWhitespace(string $code, int $intensity): string {
        if ($intensity < 40) return $code;

        $lines = explode("\n", $code);
        $result = [];
        $wsChance = $intensity / 400;

        foreach ($lines as $line) {
            if (mt_rand(1, 100) <= $wsChance * 100 && !empty(trim($line))) {
                $spaces = mt_rand(1, 2);
                $result[] = $line . str_repeat(' ', $spaces);
            } else {
                $result[] = $line;
            }
        }

        return implode("\n", $result);
    }

    // === BRACKET STYLE VARIATION ===
    private function addBracketStyleVariation(string $code, int $intensity): string {
        if ($intensity < 40) return $code;

        $result = $code;

        if (mt_rand(1, 100) <= 10) {
            $result = preg_replace('/\)\s*\{\s*$/m', ') {', $result, 1);
        }

        return $result;
    }

    // === QUOTE MIXING ===
    private function mixStringQuotes(string $code, array $lang, int $intensity): string {
        if ($intensity < 30) return $code;
        if (count($lang['string_delimiters']) < 2) return $code;

        $hasBacktick = in_array('`', $lang['string_delimiters']);

        $lines = explode("\n", $code);
        $result = [];

        foreach ($lines as $line) {
            $modified = $line;

            if (mt_rand(1, 100) <= 12) {
                $primary = $lang['string_delimiters'][0];
                $alternate = $lang['string_delimiters'][1] ?? $primary;

                $modified = preg_replace_callback('/"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"/', function($m) use ($alternate, $hasBacktick) {
                    $inner = $m[1];
                    if (str_contains($inner, $alternate)) return $m[0];
                    if ($hasBacktick && $alternate === '`') return $m[0];
                    if (str_contains($inner, '$')) return $m[0];
                    if (str_contains($inner, '\\n') || str_contains($inner, '\\t')) return $m[0];
                    if (strlen($inner) < 3) return $m[0];
                    return $alternate . $inner . $alternate;
                }, $modified, 1);
            }

            $result[] = $modified;
        }

        return implode("\n", $result);
    }

    private function getIndent(string $line): string {
        preg_match('/^(\s*)/', $line, $m);
        return $m[1] ?? '';
    }
}
