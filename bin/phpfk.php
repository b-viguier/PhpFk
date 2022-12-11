<?php

require __DIR__ . '/../vendor/autoload.php';

echo sprintf("<?php\n%s\n?>", \bviguier\PhpFk\obfuscateCode($argv[1]));
