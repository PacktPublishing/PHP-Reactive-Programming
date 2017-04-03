<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\EventDispatcher\Event;

class MyEvent extends Event
{
    private $name;
    private $counter = 0;

    public function __construct($name = null, $counter = 0)
    {
        $this->name = $name;
        $this->counter = $counter;
    }

    public function inc()
    {
        $this->counter++;
    }

    public function getCounter()
    {
        return $this->counter;
    }

    public function __toString()
    {
        return sprintf('%s (%d)', $this->name, $this->counter);
    }
}