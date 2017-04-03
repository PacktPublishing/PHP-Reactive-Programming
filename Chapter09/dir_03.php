<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';

use Rx\Observable;

$dirIter = new \RecursiveDirectoryIterator('../Chapter 08');
$iter = new \RecursiveIteratorIterator($dirIter);

Observable::fromIterator($iter)
    ->filter(function(SplFileInfo $file) {
        return $file->isFile();
    })
    ->map(function(SplFileInfo $file) {
        return $file->getFilename();
    })
    ->subscribe(new DebugSubject());
