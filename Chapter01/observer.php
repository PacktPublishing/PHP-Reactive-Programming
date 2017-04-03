<?php

class Observable {
    /** @var Observer[] */
    private $observers = [];
    private $id;
    static private $total = 0;

    public function __construct() {
        $this->id = ++self::$total;
    }

    public function register_observer(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function notify_observers() {
        foreach ($this->observers as $observer) {
            $observer->notify($this, func_get_args());
        }
    }

    public function __toString() {
        return sprintf('Observable #%d', $this->id);
    }
}

class Observer {
    static private $total = 0;
    private $id;

    public function __construct(Observable $observable) {
        $this->id = ++self::$total;
        $observable->register_observer($this);
    }

    public function notify($observable, $args) {
        printf("Observer #%d got \"%s\" from %s\n", $this->id, implode(', ', $args), $observable);
    }
}

$subject = new Observable();
$observer1 = new Observer($subject);
$observer2 = new Observer($subject);
$subject->notify_observers('test');
