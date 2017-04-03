<?php

require_once __DIR__ . '/../vendor/autoload.php';

use function Ratchet\Client\connect;
use React\EventLoop\StreamSelectLoop;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class GameClient extends Command
{
    protected function configure()
    {
        $this->setName('chat-client');
        $this->addArgument('port', InputArgument::REQUIRED);
        $this->addArgument('address', InputArgument::OPTIONAL, '', '127.0.0.1');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = $input->getArgument('port');
        $address = $input->getArgument('address');

        $stdin = fopen('php://stdin', 'r');
        $loop = new StreamSelectLoop();

        connect('ws://' . $address . ':' . $port, [], [], $loop)->then(function($conn) use ($loop, $stdin, $output) {
            $loop->addReadStream($stdin, function($stream) use ($conn, $output) {
                $str = trim(fgets($stream, 1024));
                $conn->send($str);

                $output->writeln("> ${str}");
            });

            $conn->on('message', function($str) use ($conn, $output) {
                $output->writeln("< ${str}");
            });
        }, function ($e) use ($output) {
            $output->writeln("Could not connect: {$e->getMessage()}");
        });
    }
}

$command = new GameClient();
$application = new Application();
$application->add($command);
$application->setDefaultCommand($command->getName());
$application->run();