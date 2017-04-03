<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';
require_once __DIR__ . '/ReactiveEventDispatcher.php';
require_once __DIR__ . '/MyEvent.php';

use Symfony\Component\EventDispatcher\Event;
use Rx\Observable;
use Rx\Observer\CallbackObserver;

//    $event->stopPropagation();

$dispatcher = new ReactiveEventDispatcher();

$dispatcher->addListener('my.action', function(Event $event) {
    echo "Listener #1\n";
});
$dispatcher->addListener('my.action', new CallbackObserver(function($event) {
    echo "Listener #2\n";
}), 1);
//$dispatcher->addObservable('my.action', function(Observable $observable) {
//    $observable
//        ->map(function($value) { return $value; })
//        ->filter(function($value) { return true; })
//        ->subscribe(new DebugSubject());
//});
//$dispatcher->addListener('my.action', function(Event $event) {
//    echo "Listener #4\n";
//});

$dispatcher->dispatch('my.action', new MyEvent());
