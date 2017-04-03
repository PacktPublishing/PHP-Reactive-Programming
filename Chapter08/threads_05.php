<?php

class Task extends Thread {
    public $result;
    private $i;

    public function __construct($i, Volatile $results) {
        $this->i = $i;
        $this->results = $results;
    }

    public function run() {
        sleep(1);
        $result = pow($this->i, 2);
        printf("%s: done %d\n", date('H:i:s'), $result);

        $this->results->synchronized(function($results) use ($result) {
            $results[] = (array)['id' => $this->i, 'result' => $result];
            $results->notify();
        }, $this->results);
    }
}

//class MyWorker extends Worker {
//    public function run() {
//        printf("%s: starting worker\n", date('H:i:s'));
//    }
//}

$pool = new Pool(2, Worker::class);
$results = new Volatile();

foreach (range(0, 3) as $i) {
    $pool->submit(new Task($i, $results));
}

$results->synchronized(function() use ($results) {
    while (count($results) != 4) {
        $results->wait();
    }
});

while ($pool->collect()) continue;

$pool->shutdown();

print_r($results);

echo "All done\n";
