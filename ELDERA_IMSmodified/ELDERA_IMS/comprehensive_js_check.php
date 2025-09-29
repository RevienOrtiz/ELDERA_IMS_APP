<?php

$file = 'resources/views/forms/form_existing_senior.blade.php';
$content = file_get_contents($file);

// Extract the main JavaScript content (the third script block)
preg_match_all('/<script>(.*?)<\/script>/s', $content, $matches);

if (isset($matches[1][2])) { // Third script block (index 2)
    $jsContent = $matches[1][2];
    
    $lines = explode("\n", $jsContent);
    $braceCount = 0;
    $lineNum = 0;
    $functionStack = [];
    $issues = [];
    
    foreach ($lines as $line) {
        $lineNum++;
        $originalLine = trim($line);
        
        // Skip empty lines and comments
        if (empty($originalLine) || strpos($originalLine, '//') === 0) {
            continue;
        }
        
        // Count braces
        $openBraces = substr_count($originalLine, '{');
        $closeBraces = substr_count($originalLine, '}');
        
        // Track function definitions
        if (strpos($originalLine, 'function ') !== false && strpos($originalLine, '{') !== false) {
            preg_match('/function\s+(\w+)/', $originalLine, $funcMatches);
            $funcName = isset($funcMatches[1]) ? $funcMatches[1] : 'anonymous';
            $functionStack[] = ['name' => $funcName, 'line' => $lineNum, 'braces' => $braceCount + $openBraces];
        }
        
        // Track anonymous functions and closures
        if (strpos($originalLine, 'function(') !== false || strpos($originalLine, '=>') !== false) {
            $functionStack[] = ['name' => 'anonymous', 'line' => $lineNum, 'braces' => $braceCount + $openBraces];
        }
        
        $braceCount += $openBraces - $closeBraces;
        
        // Check for issues
        if ($braceCount < 0) {
            $issues[] = "❌ Extra closing brace on line $lineNum: $originalLine";
            break;
        }
        
        // Check for catch without proper try structure
        if (strpos($originalLine, '.catch(') !== false) {
            echo "🔍 Found .catch() on line $lineNum: $originalLine\n";
            echo "   Current brace count: $braceCount\n";
            echo "   Function stack depth: " . count($functionStack) . "\n";
            
            if ($braceCount > 0) {
                $issues[] = "⚠️  Potential missing closing brace(s) before .catch() on line $lineNum";
                echo "   ⚠️  There are $braceCount unclosed braces before this .catch()\n";
                
                // Show the last few functions in the stack
                if (!empty($functionStack)) {
                    echo "   📚 Function stack:\n";
                    foreach (array_slice($functionStack, -3) as $func) {
                        echo "      - {$func['name']} (line {$func['line']})\n";
                    }
                }
            }
        }
        
        // Pop function stack when we close braces
        if ($closeBraces > 0 && !empty($functionStack)) {
            for ($i = 0; $i < $closeBraces; $i++) {
                if (!empty($functionStack)) {
                    $poppedFunc = array_pop($functionStack);
                    echo "✅ Closed function '{$poppedFunc['name']}' from line {$poppedFunc['line']} on line $lineNum\n";
                }
            }
        }
    }
    
    echo "\n=== SUMMARY ===\n";
    echo "Final brace count: $braceCount\n";
    echo "Unclosed functions: " . count($functionStack) . "\n";
    
    if (!empty($functionStack)) {
        echo "Unclosed functions:\n";
        foreach ($functionStack as $func) {
            echo "  - {$func['name']} (line {$func['line']})\n";
        }
    }
    
    if (!empty($issues)) {
        echo "\n=== ISSUES FOUND ===\n";
        foreach ($issues as $issue) {
            echo $issue . "\n";
        }
    }
    
    if ($braceCount > 0) {
        echo "❌ Missing $braceCount closing brace(s)\n";
    } elseif ($braceCount < 0) {
        echo "❌ Extra " . abs($braceCount) . " closing brace(s)\n";
    } else {
        echo "✅ Braces are balanced\n";
    }
    
} else {
    echo "Could not find the main JavaScript block\n";
}

echo "Done!\n";

