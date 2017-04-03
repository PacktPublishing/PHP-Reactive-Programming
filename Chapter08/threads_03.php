<?php

class MyJob extends Threaded
{
    public $result;
    protected $i;

    public function __construct($i)
    {
        $this->i = $i;
    }

    public function run()
    {
        sleep(rand(1, 5));
        $this->result = pow($this->i, 2);
        printf("%d: done\n", $this->i);
    }
}

$worker = new Worker();

$threads = [];
foreach (range(5, 7) as $i) {
    $thread = new MyJob($i);
    $worker->stack($thread);
    $threads[] = $thread;
}

$worker->start();

echo "Starting worker\n";

$thread = new MyJob(42);
$worker->stack($thread);
$threads[] = $thread;
$worker->shutdown();

foreach ($threads as $i => $thread) {
    printf("%d: %d\n", $i, $thread->result);
}

echo "All done\n";