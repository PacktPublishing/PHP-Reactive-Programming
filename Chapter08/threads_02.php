<?php

class MyThread extends Thread
{
    public $obj;
    public $objCopy;

    public function run()
    {
        $this->objCopy = $this->obj;
        $this->objCopy->prop2 = 'bar';
        printf("%d\n", $this->obj === $this->obj);
    }
}

$obj = new stdClass();
$obj->prop = 'foo';

$thread = new MyThread($obj);
$thread->obj = $obj;
$thread->start();
$thread->join();

printf("%d\n", $obj === $thread->objCopy);
print_r($obj);
