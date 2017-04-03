<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/MyEvent.php';

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Rx\Observable;

interface EventObservableSubscriberInterface extends EventSubscriberInterface
{

    public static function getSubscribedEventsObservables();

}