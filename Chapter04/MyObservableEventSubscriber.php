<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/MyEvent.php';
require_once __DIR__ . '/MyEventSubscriber.php';
require_once __DIR__ . '/EventObservableSubscriberInterface.php';

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Rx\Observable;

class MyObservableEventSubscriber extends MyEventSubscriber implements EventObservableSubscriberInterface
{

    public static function getSubscribedEventsObservables()
    {
        return [
            'my_action' => [
                [
                    function(Observable $observable) {
                        $observable
                            ->subscribe(new DebugSubject());
                    }, 10
                ], [
                    function(Observable $observable) {
                        $observable
                            ->subscribe(new DebugSubject());
                    }
                ]
            ],
            'other_action' => function(Observable $observable) {
                $observable
                    ->subscribe(new DebugSubject());
            }
        ];
    }

}