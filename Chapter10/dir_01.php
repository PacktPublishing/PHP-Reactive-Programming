<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';

use Rx\Observable;


Observable::create(function(\Rx\ObserverInterface $observer) {
        $start = microtime(true);
        $dirIter = new \RecursiveDirectoryIterator(__DIR__ . '/../symfony_template');
        $iter = new \RecursiveIteratorIterator($dirIter);
        $filteredIter = new \RegexIterator($iter, '/.+\.php$/', RegexIterator::GET_MATCH);

        Observable::fromIterator($filteredIter)
            ->filter(function($filenames) {
                return is_readable($filenames[0]);
            })
            ->filter(function($filenames) {
                return filesize($filenames[0]) > 2000;
            })
            ->pluck(0)
            ->subscribe($observer);

        return new \Rx\Disposable\CallbackDisposable(function() use ($start) {
            echo "duration: " . round(microtime(true) - $start, 2) . "s\n";
        });
    })
    ->count()
    ->subscribeCallback(function($files) {
        var_dump($files);
    });
