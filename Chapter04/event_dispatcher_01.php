<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;

$dispatcher = new EventDispatcher();

$dispatcher->addListener('my_action', function(Event $event) {
    echo "Listener #1\n";
//    $event->stopPropagation();
});
$dispatcher->addListener('other_action', function(Event $event) {
    echo "Other listener\n";
});
$dispatcher->addListener('my_action', function(Event $event) {
    echo "Listener #2\n";
});

$dispatcher->dispatch('my_action');
