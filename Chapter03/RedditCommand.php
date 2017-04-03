<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../Chapter 02/CURLObservable.php';
require_once '../Chapter 02/JSONDecodeOperator.php';

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Rx\Observable\IntervalObservable;
use Symfony\Component\Process\Process;

class RedditCommand extends Command
{

    /** @var \Rx\Subject\Subject */
    private $subject;

    /** @var string */
    private $subreddit;

    /** @var \Rx\DisposableInterface */
    private $subredditDisposable;

    /** @var \Rx\DisposableInterface */
    private $articleDetailDisposable;

    /** @var \Rx\DisposableInterface */
    private $backActionDisposable;

    /** @var OutputInterface */
    private $output;

    private $scheduler;
    private $loop;
    /** @var Process */
    private $process;

    /** @var IntervalObservable */
    private $intervalObservable;

//    private $curlObservable;

    const API_URL = 'https://www.reddit.com/r/%s/new.json';

    public function __construct($name = null)
    {
        parent::__construct($name);

    }

    protected function configure()
    {
        $this->setName('reddit');
        $this->setDescription('CLI Reddit reader created using RxPHP library.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->subject = new \Rx\Subject\Subject();

        $this->output = $output;

        $stdin = fopen('php://stdin', 'r');
        stream_set_blocking($stdin, false);

        $loop = new React\EventLoop\StreamSelectLoop();
        $scheduler = new Rx\Scheduler\EventLoopScheduler($loop);
        $this->intervalObservable = new IntervalObservable(100, $scheduler);

        $disposable = $this->intervalObservable
            ->map(function() use ($stdin) {
                return trim(fread($stdin, 1024));
            })
            ->filter(function($str) {
                return strlen($str) > 0;
            })
            ->subscribe($this->subject);

        $this->subject
            ->filter(function($value) {
                return strval($value) == 'q';
            })
            ->take(1)
            ->subscribeCallback(null, null,
                    function() use ($disposable, $output, $stdin) {
                fclose($stdin);
                $output->writeln('<comment>Good bye!</comment>');
                if ($this->process && $this->process->isRunning()) {
                    $this->process->stop();
                }
                $disposable->dispose();
            }
        );

        $this->askSubreddit();

        $loop->run();
    }

    protected function askSubreddit()
    {
        $this->clearScreen();
        $this->output->write('Enter subreddit name: ');
        $this->subredditDisposable = $this->subject->subscribeCallback(function($value) {
            $this->subreddit = $value;
            $this->subredditDisposable->dispose();
            $this->refreshList();
        });
    }

    protected function refreshList()
    {
        $this->process = new Process('php wrap_curl.php curl ' . sprintf(self::API_URL, $this->subreddit));
        $this->process->start();

        $this->intervalObservable
            ->takeWhile(function() {
                return $this->process->isRunning();
            })
            ->subscribeCallback(null, null, function() {
                $jsonString = $this->process->getOutput();
                if (!$jsonString) {
                    return;
                }

                $response = json_decode($jsonString, true);
                $articles = $response['data']['children'];

                $this->clearScreen();
                foreach ($articles as $i => $entry) {
                    $this->output->writeln("<info>${i}</info> " . $entry['data']['title']);
                }

                $this->printHelp();
                $template = ', <info>[%d-%d]</info>: Read article';
                $this->output->writeln(sprintf($template, 0, count($articles)));

                $this->chooseArticleDetail($articles);
            });

//        $curlObservable = new CurlObservable(sprintf(self::API_URL, $this->subreddit));
//        $curlObservable
//            ->filter(function($value) {
//                return is_string($value);
//            })
//            ->lift(function() {
//                return new JSONDecodeOperator();
//            })
//            ->subscribeCallback(function(array $response) {
//                $articles = $response['data']['children'];
//
//                $this->clearScreen();
//                foreach ($articles as $i => $entry) {
//                    $this->output->writeln("<info>${i}</info> " . $entry['data']['title']);
//                }
//
//                $this->printHelp();
//                $template = ', <info>[%d-%d]</info>: Read article';
//                $this->output->writeln(sprintf($template, 0, count($articles)));
//
//                $this->chooseArticleDetail($articles);
//            }, function($e) {
//                $this->output->writeln('<error>Unable to download data from Reddit API</error>');
//            });
    }

    protected function chooseArticleDetail($articles)
    {
        $this->articleDetailDisposable = $this->subject
            ->filter(function($index) use ($articles) {
                return is_numeric($index) &&
                    $index >= 0 && $index < count($articles);
            })
            ->subscribeCallback(function($value) use ($articles) {
                $this->articleDetailDisposable->dispose();
                $this->showArticleDetail($articles[$value]['data']);
            });
    }

    protected function showArticleDetail($article)
    {
        $this->clearScreen();

        $this->output->writeln($article['title'] . "\n");
        $this->output->writeln(html_entity_decode($article['selftext']));

        $this->printHelp();
        $this->output->writeln(', <info>[b]</info> Back to the list');

        $this->backActionDisposable = $this->subject
            ->filter(function($value) {
                return 'b' == $value;
            })
            ->subscribeCallback(function($value) {
                $this->backActionDisposable->dispose();
                $this->refreshList();
            });
    }


    protected function printHelp()
    {
        $this->output->writeln('');
        $this->output->write('<info>[q]</info> Quit');
    }

    protected function clearScreen()
    {
        $this->output->write(sprintf("\033\143"));
    }

}



