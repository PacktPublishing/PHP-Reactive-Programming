<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 03/ProcessObservable.php';
require_once __DIR__ . '/GameServerStreamEndpoint.php';

use Rx\Scheduler\EventLoopScheduler;
use Rx\Subject\Subject;
use Rx\Observable;
use Rx\DisposableInterface;
use React\EventLoop\LoopInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class ServerManagerCommand extends Command
{
    private $unixSocketFile;

    /** @var GameServerStreamEndpoint[] */
    private $servers = [];

    /** @var DisposableInterface[] */
    private $processes = [];

    /** @var Subject */
    private $statusSubject;

    /** @var LoopInterface */
    private $loop;

    /** @var OutputInterface */
    private $output;

    private $startPort = 8888;

    private $scheduler;

    private $commands = [
        'n' => 'spawnNewServer',
        'q' => 'quit',
    ];

    protected function configure()
    {
        $this->setName('manager');
        $this->addArgument('socket_file', InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->unixSocketFile = $input->getArgument('socket_file');
        $this->output = $output;

        @unlink($this->unixSocketFile);
        $server = stream_socket_server("unix://" . $this->unixSocketFile, $errno, $errorMessage);
        stream_set_blocking($server, 0);

        $output->writeln('Listening on socket ' . $this->unixSocketFile);

        $this->loop = new React\EventLoop\StreamSelectLoop();
        $this->scheduler = new EventLoopScheduler($this->loop);

        // Handle incoming connection from spawned sub-process
        $this->loop->addReadStream($server, function() use ($server) {
            $client = stream_socket_accept($server);
            $server = new GameServerStreamEndpoint($client, $this->loop);

            $server->onInit()->then(function($port) use ($server) {
                $this->output->writeln(sprintf('Sub-process %d initialized', $port));
                $this->addServer($port, $server);
            });
        });

        $subject = new Subject();
        $stdin = $subject->asObservable();

        $stdinHandler = fopen('php://stdin', 'r');
        $this->loop->addReadStream($stdinHandler, function($stream) use ($subject) {
            $str = trim(fgets($stream, 1024));
            $subject->onNext($str);
        });

        foreach ($this->commands as $pattern => $method) {
            $stdin
                ->filter(function($string) use ($pattern) {
                    return $pattern == $string;
                })
                ->subscribeCallback(function($value) use ($method) {
                    $this->$method($value);
                });
        }

        $this->statusSubject = new Subject();
        $this->statusSubject
            ->map(function() {
                $observables = array_map(function(GameServerStreamEndpoint $server) {
                    return $server->getStatus();
                }, $this->servers);

                return Observable::just(true)
                    ->combineLatest($observables, function($array) {
                        $values = func_get_args();
                        array_shift($values);
                        return $values;
                    });
            })
            ->switchLatest()
            ->map(function($statuses) {
                $updatedStatuses = [];
                $ports = array_keys($this->servers);
                foreach ($statuses as $index => $status) {
                    $updatedStatuses[$ports[$index]] = $status;
                }
                return $updatedStatuses;
            })
            ->subscribeCallback(function($statuses) use ($output) {
                $output->write(sprintf("\033\143"));
                foreach ($statuses as $port => $status) {
                    $str = sprintf("%d: %s", $port, $status);
                    $output->writeln($str);
                }
            });

        $this->output->writeln('Running ...');

        $this->loop->run();
    }

    private function addServer($port, GameServerStreamEndpoint $server) {
        $this->servers[$port] = $server;
        $this->statusSubject->onNext(1);
    }

    private function spawnNewServer() {
        $port = $this->startPort++;
        $cmd = escapeshellcmd('php GameServer.php game-server ' . $this->unixSocketFile . ' ' . $port);
        $process = new ProcessObservable($cmd);

        $this->output->writeln('Spawning new process on port ' . $port);

        $this->processes[$port] = $process->subscribeCallback(null,
            function($e) use ($port) {
                $this->output->writeln(sprintf('%d: Error "%s"', $port, $e));
            },
            function() use ($port) {
                $this->output->writeln(sprintf('%d: Ended', $port));
            }, $this->scheduler
        );
    }

    private function quit() {
        foreach ($this->servers as $server) {
            $server->close();
        }
        foreach ($this->processes as $process) {
            $process->dispose();
        }
        $this->loop->stop();
    }

}

$command = new ServerManagerCommand();
$application = new Application();
$application->add($command);
$application->setDefaultCommand($command->getName());
$application->run();