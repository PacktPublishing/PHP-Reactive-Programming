<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/EventObservableSubscriberInterface.php';

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Rx\Subject\Subject;
use Rx\ObserverInterface;
use Rx\ObservableInterface;
use Rx\Observable;
use Rx\Observer\CallbackObserver;
use Rx\Operator\AsObservableOperator;


class ReactiveEventDispatcher extends EventDispatcher {

    /**
     * @var Subject[];
     */
    private $subjects = [];

    /**
     * @param string $eventName
     * @param Event|null $event
     * @return Event
     */
    public function dispatch($eventName, Event $event = null)
    {
        if (null === $event) {
            $event = new Event();
        }

        $this->getSubject($eventName)->onNext($event);

        return $event;
    }

    /**
     * @param string $eventName
     * @param callable|ObserverInterface $listener
     * @param int $priority
     * @throws Exception
     */
    public function addListener($eventName, $listener, $priority = 0)
    {
        $observer = $this->observerFromListener($listener);
        parent::addListener($eventName, $observer, $priority);

        unset($this->subjects[$eventName]);
    }

    /**
     * @param string $eventName
     * @param callable $createChain
     * @param int $priority
     */
    public function addObservable($eventName, $createOperatorChain, $priority = 0)
    {
        $subject = new Subject();
        $createOperatorChain($subject->asObservable());
        $this->addListener($eventName, $subject, $priority);
    }

    /**
     * @inheritdoc
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        parent::addSubscriber($subscriber);

        if ($subscriber instanceof EventObservableSubscriberInterface) {
            $events = $subscriber->getSubscribedEventsObservables();
            foreach ($events as $evt => $params) {
                if (is_callable($params)) {
                    $this->addObservable($evt, $params);
                } else {
                    foreach ($params as $listener) {
                        $prio = isset($listener[1]) ? $listener[1] : 0;
                        $this->addObservable($evt, $listener[0], $prio);
                    }
                }
            }
        }
    }

    /**
     * @param callable|ObserverInterface|Observable $listener
     * @return ObserverInterface
     */
    private function observerFromListener($listener)
    {
        if (is_callable($listener)) {
            return new CallbackObserver($listener);
        } elseif ($listener instanceof ObserverInterface) {
            return $listener;
        } else {
            throw new \Exception();
        }
    }

    /**
     * @param string $eventName
     * @return Subject
     */
    private function getSubject($eventName)
    {
        if (isset($this->subjects[$eventName])) {
            return $this->subjects[$eventName];
        }

        $subject = new Subject();
        $this->subjects[$eventName] = $subject;
        $tail = $subject->asObservable();

        foreach ($this->getListeners($eventName) as $listener) {
            $newTail = $tail->filter(function (Event $event) {
                return !$event->isPropagationStopped();
            });
            $newTail->subscribe($listener);
            $tail = $newTail;
        }

        return $subject;
    }

}