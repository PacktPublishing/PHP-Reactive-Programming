<?php

$name = $argv[1];
$time = intval($argv[2]);
$elapsed = 0;

while ($elapsed < $time) {
    sleep(1);
    $elapsed++;
    printf("$name: $elapsed\n");
}
