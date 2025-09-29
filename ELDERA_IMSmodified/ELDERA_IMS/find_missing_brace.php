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
    
    foreach ($lines as $line) {
        $lineNum++;
        $originalLine = $line;
        $line = trim($line);
        
        // Count braces
        $openBraces = substr_count($line, '{');
        $closeBraces = substr_count($line, '}');
        $braceCount += $openBraces - $closeBraces;
        
        // Check for lines that might indicate missing braces
        if ($braceCount < 0) {
            echo "❌ Extra closing brace on line $lineNum: $originalLine\n";
            break;
        }
        
        // Check for potential issues
        if (strpos($line, 'catch') !== false && strpos($line, 'try') === false) {
            echo "⚠️  Catch without try on line $lineNum: $originalLine\n";
            echo "Current brace count: $braceCount\n";
        }
        
        // Check for function definitions that might be missing opening braces
        if (strpos($line, 'function') !== false && strpos($line, '{') === false && strpos($line, '=>') === false) {
            echo "⚠️  Function without opening brace on line $lineNum: $originalLine\n";
        }
        
        // Check for lines ending with opening brace (might be missing closing)
        if (substr($line, -1) === '{' && $braceCount > 0) {
            echo "⚠️  Potential missing closing brace on line $lineNum: $originalLine\n";
        }
    }
    
    echo "\nFinal brace count: $braceCount\n";
    
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

