<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';
require_once __DIR__ . '/ReactiveEventDispatcher.php';
require_once __DIR__ . '/MyEvent.php';

use Symfony\Component\EventDispatcher\Event;
use Rx\Subject\Subject;
use Rx\Observer\CallbackObserver;


$subject = new Subject();

$tail = $subject->filter(function(Event $event) {
    return !$event->isPropagationStopped();
});
$tail->subscribe(new CallbackObserver(function(Event $event) {
    echo "Listener #1\n";
    $event->stopPropagation();
}));

$tail = $tail->filter(function(Event $event) {
    return !$event->isPropagationStopped();
});
$tail->subscribe(new CallbackObserver(function(Event $event) {
    echo "Listener #2\n";
}));

$subject->onNext(new Event());

