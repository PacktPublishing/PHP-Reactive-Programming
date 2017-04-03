<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';

use Rx\Observable;

$dirIter = new \RecursiveDirectoryIterator(__DIR__ . '/../symfony_template');
$iter = new \RecursiveIteratorIterator($dirIter);
$filteredIter = new \RegexIterator($iter, '/.+\.php$/', RegexIterator::GET_MATCH);

Observable::fromIterator($filteredIter)
    ->filter(function($filename) {
        return (bool) $filename;
    })
    ->pluck(0)
    ->subscribe(new DebugSubject());
