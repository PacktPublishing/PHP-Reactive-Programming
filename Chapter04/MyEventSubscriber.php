<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/MyEvent.php';

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MyEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'my_action' => [
                ['onMyAction'],
                ['onMyActionAgain', 1],
            ],
            'other_action' => 'onOtherAction',
        ];
    }

    public function onMyAction(MyEvent $event)
    {
        $event->inc();
        echo sprintf("Listener [onMyAction]: %s\n", $event);
    }

    public function onMyActionAgain(MyEvent $event)
    {
        $event->inc();
        echo sprintf("Listener [onMyActionAgain]: %s\n", $event);
    }

    public function onOtherAction(MyEvent $event) { }
}