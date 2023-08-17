<?php

function simpleProgressBar($progress, $total, $barLength = 30) {
    $percent = ($progress / $total) * 100;
    $numDots = ($progress / $total) * $barLength;
    $numSpaces = $barLength - $numDots;

    $bar = str_repeat('.', $numDots) . str_repeat(' ', $numSpaces);

    printf("\r[%s] %0.2f%%", $bar, $percent);
}

function find_admin($url)
{
    $url = parse_url($url)["scheme"] . "://" . parse_url($url)["host"] . "/";
    
    $wordlist_file = fopen("wordlist.txt", "r");
    $wordlist = fread($wordlist_file, filesize("wordlist.txt"));
    $wordlist_array = explode("\n", $wordlist);
    $totalSteps = count($wordlist_array);
    
    $progress = 0;

    foreach ($wordlist_array as $word) {
        $progress++;
        
        $headers = @get_headers($url . $word);
        if ($headers && strpos($headers[0], '200')) {
            echo $url . $word;
            break;
        }
        
        simpleProgressBar($progress, $totalSteps);
    }
    
    echo "\n";
}

echo "Enter url : ";
$url = fgets(STDIN);
$url = trim($url);
if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
    find_admin($url);
}