<?php

class MyThread extends Thread
{
    protected $i;

    public function __construct($i)
    {
        $this->i = $i;
    }

    public function run()
    {
        sleep(rand(1, 5));
        printf("%d: done\n", $this->i);
    }
}

$threads = [];
foreach (range(0, 5) as $i) {
    $thread = new MyThread($i);
    $thread->start();
    $threads[] = $thread;
}

foreach ($threads as $thread) {
    $thread->join();
}

echo "All done\n";