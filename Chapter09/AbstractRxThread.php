<?php

abstract class AbstractRxThread extends Thread
{

    private $done = false;
    protected $result;

    public function getResult()
    {
        return $this->result;
    }

    public function isDone()
    {
        return $this->done;
    }

    protected function markDone()
    {
        $this->done = true;
    }

}