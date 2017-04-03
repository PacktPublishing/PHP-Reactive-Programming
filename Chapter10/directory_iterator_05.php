<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';
require_once __DIR__ . '/DirectoryIteratorObservable.php';

use Rx\Observable;

Observable::create(function(\Rx\ObserverInterface $observer) {
        $start = microtime(true);
        (new DirectoryIteratorObservable(__DIR__ . '/../symfony_template'))
            ->filter(function(SplFileInfo $file) {
                return $file->isReadable();
            })
            ->filter(function(SplFileInfo $file) {
                return $file->getSize() > 2000;
            })
            ->subscribe($observer);

        return new \Rx\Disposable\CallbackDisposable(function() use ($start) {
            echo "duration: " . round(microtime(true) - $start, 2) . "s\n";
        });
    })
    ->count()
    ->subscribeCallback(function($files) {
        var_dump($files);
    });

