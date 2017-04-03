<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 05/ForkJoinObservable.php';
require_once __DIR__ . '/AssignmentInConditionNodeVisitor.php';

use Rx\ObservableInterface;
use Rx\ObserverInterface;
use Rx\SchedulerInterface;
use Rx\Operator\OperatorInterface;
use Rx\Observer\CallbackObserver;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class PHPParserOperator implements OperatorInterface
{
    private $parser;
    private $traverserClasses;

    public function __construct($traverserClasses = [])
    {
        $syntax = ParserFactory::PREFER_PHP7;
        $this->parser = (new ParserFactory())->create($syntax);
        $this->traverserClasses = $traverserClasses;
    }

    private function createTraverser()
    {
        $traverser = new NodeTraverser();
        $visitors = array_map(function($class) use ($traverser) {
            /** @var ObservableNodeVisitorInterface $visitor */
            $visitor = new $class();
            $traverser->addVisitor($visitor);

            return $visitor->asObservable()
                ->toArray()
                ->map(function($violations) use ($class) {
                    return [
                        'violations' => $violations,
                        'class' => $class
                    ];
                });
        }, $this->traverserClasses);

        return [$traverser, $visitors];
    }

    public function __invoke(ObservableInterface $observable, ObserverInterface $observer, SchedulerInterface $scheduler = null)
    {
        $onNext = function($filepath) use ($observer) {
            $code = @file_get_contents($filepath);
            if (!$code) {
                $e = new \Exception('Unable to read file ' . $filepath);
                $observer->onError($e);
                return;
            }

            list($traverser, $visitors) = $this->createTraverser();
            (new ForkJoinObservable($visitors))
                ->map(function($results) use ($filepath) {
                    // $results = collection of results from all node visitors.
                    $filtered = array_filter($results, function($result) {
                        return $result['violations'];
                    });
                    return [
                        'file' => $filepath,
                        'results' => $filtered,
                    ];
                })
                ->subscribeCallback(function($result) use ($observer) {
                    $observer->onNext($result);
                });

            $stmts = $this->parser->parse($code);
            $traverser->traverse($stmts);
        };

        $callbackObserver = new CallbackObserver(
            $onNext,
            [$observer, 'onError'],
            [$observer, 'onCompleted']
        );

        return $observable->subscribe($callbackObserver, $scheduler);
    }
}
