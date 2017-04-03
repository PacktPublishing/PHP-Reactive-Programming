<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';
require_once __DIR__ . '/ThreadPoolOperator.php';
require_once __DIR__ . '/AbstractRxThread.php';

use Rx\Observable;
use Rx\ObserverInterface;
use Rx\Scheduler\EventLoopScheduler;
use Rx\Disposable\CallbackDisposable;
use React\EventLoop\StreamSelectLoop;

class TestTask extends AbstractRxThread
{
    private $num;
    private $id;

    public function __construct($id, $num)
    {
        $this->id = $id;
        $this->num = $num;
    }

    public function run()
    {
        sleep(rand(1, 4));
        $result = pow($this->num, 2);

        $this->result = (array)['id' => $this->id, 'result' => $result];

        $this->markDone();
    }
}

$loop = new StreamSelectLoop();
$scheduler = new EventLoopScheduler($loop);

Observable::create(function(ObserverInterface $observer) use ($loop) {
        foreach (range(6, 9) as $id => $num) {
            $observer->onNext([$id, $num]);
        }

        return new CallbackDisposable(function() use ($loop) {
            $loop->stop();
        });
    })
    ->map(function($args) {
        var_dump($args);
        return new TestTask(...$args);
    })
    ->lift(function() {
        return new ThreadPoolOperator(2);
    })
    ->take(4)
    ->subscribe(new DebugSubject(), $scheduler);

$loop->run();