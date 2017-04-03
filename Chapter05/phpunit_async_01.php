<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

function asyncPowIterator($num, callable $callback) {
    foreach (range(1, $num - 1) as $i) {
        $callback($i, pow($i, 2));
    }
}

class AsyncDemoTest extends TestCase {

    public function testBrokenAsync() {
        $count = 0;
        $callback = function($i, $pow) use (&$count) {
            $this->assertEquals(pow($i, 2), $pow);
            $count++;
        };
        asyncPowIterator(5, $callback);
        $this->assertEquals(5, $count);
    }

}