<?php

class PrintObserver extends \Rx\Observer\AbstractObserver {
    protected function completed() {
        print("Completed\n");
    }

    protected function next($value) {
        print(sprintf("Next: %s\n", $value));
    }

    protected function error(Exception $error) {
        print("Error\n");
    }
}
