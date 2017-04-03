<?php

$stdin = fopen('php://stdin', 'r');
stream_set_blocking($stdin, false);

$readStreams = [$stdin];
$writeStreams = [];
$exceptStreams = [];

stream_select($readStreams, $writeStreams, $exceptStreams, 5);

echo "stdin: " . strrev(fgets($stdin));
