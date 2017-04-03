<?php

$source = fopen('textfile.txt', 'r');
fseek($source, 5);
$dest = fopen('destfile.txt', 'w');

stream_copy_to_stream($source, $dest);
