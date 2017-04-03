<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/DirectoryIteratorObservable.php';

use Rx\ObservableInterface;
use Rx\Observable;
use Rx\ObserverInterface;
use Rx\SchedulerInterface;
use Rx\Scheduler\ImmediateScheduler;

class DirectoryIteratorSharedObservable extends Observable
{
    private $inner;

    public function __construct()
    {
        $args = func_get_args();
        $this->inner = (new DirectoryIteratorObservable(...$args))
            ->publish();
    }

    public function subscribe(ObserverInterface $observer, $scheduler = null)
    {
        $this->inner->subscribe($observer, $scheduler);
    }

    public function connect()
    {
        return $this->inner->connect();
    }

    public function refCount()
    {
        return $this->inner->refCount();
    }
}