<?php

use PHPUnit\Framework\TestCase;

class DemoTest extends TestCase {

    public function testFirstTest() {
        $expectedVar = 5;
        $this->assertTrue(5 == $expectedVar);
        $this->assertEquals(5, $expectedVar);

        $expectedArray = [1, 2, 3];
        $this->assertEquals([1, 2, 3], $expectedArray);
        $this->assertContains(2, $expectedArray);
    }

    public function testFails() {
        $this->assertEquals(5, 6);
        $this->assertContains(2, [1, 3, 4]);
    }

    /** @depends testFails */
    public function testDepends() {
        $this->assertTrue(true);
    }

}
