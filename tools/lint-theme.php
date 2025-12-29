<?php
$dir = __DIR__ . '/../profit-benefit';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($rii as $file) {
    if ($file->isDir()) continue;
    if (strtolower($file->getExtension()) !== 'php') continue;
    $path = $file->getPathname();
    echo '--- ' . substr($path, strlen(__DIR__) + 1) . PHP_EOL;
    $cmd = 'php -l ' . escapeshellarg($path);
    passthru($cmd);
}
