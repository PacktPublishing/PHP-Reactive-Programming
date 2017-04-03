<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';
require_once __DIR__ . '/ReactiveEventDispatcher.php';
require_once __DIR__ . '/MyObservableEventSubscriber.php';
require_once __DIR__ . '/MyEvent.php';

use Symfony\Component\EventDispatcher\Event;
use Rx\Subject\Subject;
use Rx\Observer\CallbackObserver;
use Rx\Observable;

$dispatcher = new ReactiveEventDispatcher();
$dispatcher->addSubscriber(new MyObservableEventSubscriber());
$dispatcher->dispatch('my_action', new MyEvent('my-event'));
