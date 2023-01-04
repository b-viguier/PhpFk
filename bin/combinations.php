<?php

$list = str_split($argv[1]);

function combinations(array $list): iterable
{
    for ($i = 0; $i < count($list); ++$i) {
        $current = $list[$i];
        yield $current => [$current];
        foreach (combinations(array_slice($list, $i + 1)) as $other => $combination) {
            $char = $current ^ $other;
            yield $char => array_merge($combination, [$current]);
        }
    }
}

$combinationsMap = [];
foreach (combinations($list) as $char => $combination) {
    if (ord($char) < 32) continue;
    if (!isset($combinationsMap[$char]) || count($combinationsMap[$char]) > count($combination))
        $combinationsMap[$char] = $combination;
}

ksort($combinationsMap);
foreach ($combinationsMap as $char => $combination) {
    echo "'$char' => \"('" . join("'^'", $combination) . "')\",\n";
}

echo "== found functions ==\n";
$functions = get_defined_functions()['internal'];
foreach ($functions as $func) {
    $name = '';
    for ($i = 0; $i < strlen($func); ++$i) {
        $char = $func[$i];
        if (isset($combinationsMap[$char])) {
            $name .= $char;
            continue;
        }
        $char = strtoupper($char);
        if (isset($combinationsMap[$char])) {
            $name .= $char;
            continue;
        }
        continue 2;
    }
    echo "$name\n";
}
echo "== done ==\n";
