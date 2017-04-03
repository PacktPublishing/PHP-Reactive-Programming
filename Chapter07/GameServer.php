<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/GameServerStreamEndpoint.php';
require_once __DIR__ . '/StreamObservable.php';
require_once __DIR__ . '/ChatServer.php';
require_once __DIR__ . '/ThrottleTimeOperator.php';

use Rx\Observable;
use Rx\Scheduler\EventLoopScheduler;
use Ratchet\Server\IoServer;
use React\Socket\Server as Reactor;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class GameServer extends Command
{
    /** @var StreamObservable */
    private $streamObservable;

    protected function configure()
    {
        $this->setName('game-server');
        $this->addArgument('socket_file', InputOption::VALUE_REQUIRED);
        $this->addArgument('port', InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('socket_file');
        $port = $input->getArgument('port');

        $client = stream_socket_client("unix://" . $file, $errno, $errorMessage);
        stream_set_blocking($client, 0);

        $loop = new React\EventLoop\StreamSelectLoop();

        $this->streamObservable = new StreamObservable($client, $loop);
        $this->streamObservable->send('init', ['port' => $port]);
        $this->streamObservable->send('status', 'ready');
        $scheduler = new EventLoopScheduler($loop);
//        Observable::interval(500, $scheduler)
//            ->lift(function() {
//                return new ThrottleTimeOperator(1000);
//            })
//            ->subscribeCallback(function($counter) {
//                $this->streamObservable->send('status', $counter);
//        });

        // Setup WebSocket server
        $webSocketServer = new ChatServer();
        $socket = new Reactor($loop);
        $socket->listen($port, '0.0.0.0');
        $server = new IoServer(
            new HttpServer(
                new WsServer(
                    $webSocketServer
                )
            ),
            $socket,
            $loop
        );

        $webSocketServer->getObservable()
            ->subscribeCallback(function($status) {
                 $this->streamObservable->send('status', $status);
            });

        $server->run();
    }
}

$command = new GameServer();
$application = new Application();
$application->add($command);
$application->setDefaultCommand($command->getName());
$application->run();