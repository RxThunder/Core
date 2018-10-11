<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\Thunder\Console;

use Psr\Container\ContainerInterface;
use Rx\Scheduler;
use Rxnet\EventStore\EventStore;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Th3Mouk\Thunder\Router\EventStore\Adapter;
use Th3Mouk\Thunder\Router\Router;

final class EventStoreConsole extends AbstractConsole
{
    public static $expression = 'listen:persistent stream group [--middlewares=]* [--timeout=]';
    public static $description = 'EventStore persistent listener for projection';
    public static $argumentsAndOptions = [
        'stream' => 'Persistent subscription name to connect to',
        'group' => 'Consume the stream as given group name',
        '--timeout' => 'timeout',
    ];

    public static $defaults = [
        'timeout' => 10000,
    ];

    private $container;
    private $eventStore;
    private $parameterBag;
    private $router;
    private $adapter;

    public function __construct(
        ContainerInterface $container,
        EventStore $eventStore,
        ParameterBagInterface $parameterBag,
        Router $router,
        Adapter $adapter
    ) {
        $this->container = $container;
        $this->eventStore = $eventStore;
        $this->parameterBag = $parameterBag;
        $this->router = $router;
        $this->adapter = $adapter;
    }

    public function __invoke(
        string $stream,
        string $group,
        array $middlewares,
        int $timeout
    ) {
        // You only need to set the default scheduler once
        Scheduler::setDefaultFactory(
            function () {
                // The getLoop method auto start loop
                return new Scheduler\EventLoopScheduler(\EventLoop\getLoop());
            }
        );

        // Middlewares declared in config/middleware.php
//        foreach ($middlewares as $middleware) {
//            $this->middlewareProvider->append($this->container->get("middleware.{$middleware}"));
//        }

        $storeDSN = $this->parameterBag->get('eventstore.tcp');
//        $this->log->info('Connect to eventStore');
        $this->eventStore
            ->connect($storeDSN)
            ->doOnError(function (\Exception $e) {
//                $this->log->error($e->getMessage());
                var_dump($e);
                die('Crash to retry later');
            })
            ->subscribe(function () use ($stream, $group) {
                $this->eventStore->persistentSubscription($stream, $group)
                    ->flatMap($this->adapter)
//                    ->flatMap(function (AcknowledgeableEventRecord $record) {
//                        // todo handle middlewares with map
//                        echo 'xD', PHP_EOL;
//
//                        // todo send Observable into router
//
//                        // todo subscribe to ack or nack and log
//                        echo "received {$record->getId()}  {$record->getNumber()}@{$record->getStreamId()} {$record->getType()}\n";
//                        return $record->ack();
//                        //$record->nack();
//                    })
                    ->subscribe(
                        function ($subject) {
                            ($this->router)($subject);
                        },
                        function ($e) {
                            var_dump($e);
                        }
                    );
            });

//        $this->log->info("Connected to stream {$stream} as group {$group}");

//        $adapter = $this->container->make(
//            AcknowledgeableJsonAdapter::class,
//            ['timeout' => $timeout]
//        );

//        $middlewareSequence = array_merge(
//            $this->middlewareProvider->beforeAdapter(), [$adapter], $this->middlewareProvider->beforeRoute()
//        );

//        $observable = $this->eventStore->persistentSubscription($stream, $group);

//        foreach ($middlewareSequence as $selector) {
//            $observable = $observable->flatMap($selector);
//        }

//        $observable
//            ->subscribe(
//                new CallbackObserver(
////                    [$this->router, 'onNext'],
//                    null,
//                    function (\Exception $e) {
//                        die('Error in persistent subscription crash : '.$e->getMessage());
//                    }
//                ),
//                new EventLoopScheduler($this->loop)
//            );
    }
}
