<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/ReactiveEventDispatcher.php';

use Symfony\Component\EventDispatcher\Event;

$dispatcher = new ReactiveEventDispatcher();

$dispatcher->addListener('my.action', function(Event $event) {
    echo "Listener #1\n";
//    $event->stopPropagation();
}, 1);

$dispatcher->addListener('my.other_action', function(Event $event) {
    echo "Other listener #2\n";
});

$dispatcher->addListener('my.action', function(Event $event) {
    echo "Listener #2\n";
}, 2);

$dispatcher->dispatch('my.action', new Event());


$dispatcher->addListener('my.action', function(Event $event) {
    echo "Listener #3\n";
});
$dispatcher->dispatch('my.action', new Event());