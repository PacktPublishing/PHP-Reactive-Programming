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
        sleep(rand(1, 5));
        printf("%d: done\n", $this->i);
        $this->result = pow($this->i, 2);
    }
}

$threads = [];
foreach (range(5, 7) as $i) {
    $thread = new MyThread($i);
    $thread->start();
    $threads[] = $thread;
}

foreach ($threads as $i => $thread) {
    $thread->join();
    printf("%d: %d\n", $i, $thread->result);
}

echo "All done\n";