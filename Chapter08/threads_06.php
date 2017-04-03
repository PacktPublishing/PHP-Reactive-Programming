<?php

$pool = new Pool(2);

foreach (range(1, 8) as $i) {

    $pool->submit(new class($i) extends Thread
    {
        public $i;
        public $result;
        private $garbage = false;

        public function __construct($i)
        {
            $this->i = $i;
        }

        public function run()
        {
            sleep(1);
            echo "Hello World\n";
            $this->result = $this->i * 2;
            $this->garbage = true;
        }

        public function isGarbage() : bool
        {
            return $this->garbage;
        }
    });
}



while ($pool->collect(function(Collectable $task) {
    if ($task->isGarbage()) {
        echo $task->i . ' ' . $task->result . "\n";
    }
    return $task->isGarbage();
})) continue;

$pool->shutdown();
