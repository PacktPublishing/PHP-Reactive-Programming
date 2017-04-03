<?php

class Task extends Thread {
    public $result;
    private $i;

    public function __construct($i) {
        $this->i = $i;
    }

    public function run() {
        sleep(1);
        $this->result = pow($this->i, 2);
        printf("%s: done %d\n", date('H:i:s'), $this->result);
    }
}

class MyWorker extends Worker {
    public function run() {
        printf("%s: starting worker\n", date('H:i:s'));
    }
}

$pool = new Pool(2, MyWorker::class);

foreach (range(0, 3) as $i) {
    $pool->submit(new Task($i));
}

$results = [];

$pool->collect(function(Task $task) use (&$results) {
    if ($task->isComplete()) {
        echo $task->i . ' ' . $task->result . "\n";
        $results[] = $task->result;
    }
    return $task->isComplete();
});

$pool->shutdown();

print_r($results);

echo "All done\n";
