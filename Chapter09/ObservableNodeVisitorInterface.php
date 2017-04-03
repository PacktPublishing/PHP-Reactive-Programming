<?php

require_once __DIR__ . '/../vendor/autoload.php';

interface ObservableNodeVisitorInterface
{
    /**
     * @return \Rx\Observable
     */
    public function asObservable();
}