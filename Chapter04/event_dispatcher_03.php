<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/MyEvent.php';
require_once __DIR__ . '/MyEventSubscriber.php';

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new MyEventSubscriber());
$dispatcher->dispatch('my_action', new MyEvent('my-event'));