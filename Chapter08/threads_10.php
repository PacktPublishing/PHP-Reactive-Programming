<?php
$pool = new Pool(3);

while (@$i++ < 6) {
    $pool->submit(new class($i) extends Thread
            implements Collectable {
        public $id;
        private $garbage;

        public function __construct($id) {
            $this->id = $id;
        }

        public function run() {
            sleep(1);
            printf("Hello World from %d\n", $this->id);
            $this->setGarbage();
        }

        public function setGarbage() {
            $this->garbage = true;
        }

        public function isGarbage(): bool {
            return $this->garbage;
        }
    });
}

while ($pool->collect(function(Collectable $work){
    printf("Collecting %d\n", $work->id);
    return $work->isGarbage();
})) continue;

$pool->shutdown();
