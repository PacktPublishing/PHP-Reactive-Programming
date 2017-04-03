<?php

class MyThread extends Thread
{
    protected $i;
    public $result;

    public function __construct($i)
    {
        $this->i = $i;
    }

    public function run()
    {
        $this->result = pow($this->i, 2);
    }
}

$pool = new Pool(3);
$threads = [];

foreach (range(1, 7) as $i) {
    $thread = new MyThread($i);
    $pool->submit($thread);
    $threads[] = $thread;
}

$pool->shutdown();

$results = [];
foreach ($threads as $thread) {
    $results[] = $thread->result;
}

print_r($results);
