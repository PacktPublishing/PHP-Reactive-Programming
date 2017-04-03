<?php

$descriptors = [
    0 => ['pipe', 'r'], // stdin
    1 => ['pipe', 'w'], // stdout
    2 => ['file', '/dev/null', 'a'] // stderr
];

$proc = proc_open('php sleep.php proc1 3', $descriptors, $pipes);
stream_set_blocking($pipes[1], 0);

while (proc_get_status($proc)['running']) {
    usleep(100 * 1000);
    $str = fread($pipes[1], 1024);
    if ($str) {
        printf($str);
    } else {
        printf("tick\n");
    }
}

fclose($pipes[1]);
proc_close($proc);