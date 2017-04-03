<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';
require_once __DIR__ . '/ReactiveEventDispatcher.php';
require_once __DIR__ . '/MyEventSubscriber.php';
require_once __DIR__ . '/MyEvent.php';

use Symfony\Component\EventDispatcher\Event;
use Rx\Subject\Subject;
use Rx\Observer\CallbackObserver;
use Rx\Observable;

$dispatcher = new ReactiveEventDispatcher();
$dispatcher->addListener('my_action', function(MyEvent $event) {
    echo "Listener #1\n";
});
$dispatcher->addObservable('my_action', function(Observable $observable) {
    $observable
        ->map(function(MyEvent $event) {
            $event->inc();
            return $event;
        })
        ->doOnNext(function(MyEvent $event) {
            if ($event->getCounter() % 2 === 0) {
                $event->stopPropagation();
            }
        })
        ->subscribe(new DebugSubject());
}, 1);

foreach (range(0, 5) as $i) {
    $dispatcher->dispatch('my_action', new MyEvent('my-event', $i));
}
