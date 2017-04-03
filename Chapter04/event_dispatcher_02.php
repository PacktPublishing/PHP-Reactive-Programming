<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;

$dispatcher = new EventDispatcher();

$dispatcher->addListener('my_action', function(Event $event) {
    echo "Listener #1\n";
});
$dispatcher->addListener('my_action', function(Event $event) {
    echo "Listener #2\n";
    $event->stopPropagation();
}, 2);

$dispatcher->dispatch('my_action', new Event());