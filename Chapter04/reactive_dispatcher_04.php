<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';
require_once __DIR__ . '/ReactiveEventDispatcher.php';
require_once __DIR__ . '/MyEventSubscriber.php';
require_once __DIR__ . '/MyEvent.php';

use Symfony\Component\EventDispatcher\Event;
use Rx\Subject\Subject;
use Rx\Observer\CallbackObserver;


$dispatcher = new ReactiveEventDispatcher();
$dispatcher->addSubscriber(new MyEventSubscriber());

$dispatcher->dispatch('my_action', new MyEvent());
