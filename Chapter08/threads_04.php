<?php

class MyWorker extends Worker
{
    public function run()
    {
        printf("%s: starting worker\n", date('H:i:s'));
    }
}

class Task extends Threaded
{
    public function run()
    {
        sleep(3);
        printf("%s: done\n", date('H:i:s'));
    }
}

printf("%s: start\n", date('H:i:s'));
$pool = new Pool(3, MyWorker::class);

foreach (range(0, 5) as $i) {
    $pool->submit(new Task());
}

$pool->shutdown();
echo "All done\n";
