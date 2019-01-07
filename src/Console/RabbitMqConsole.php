<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Console;

use EventLoop\EventLoop;
use Rx\Scheduler;
use Rxnet\RabbitMq\Client;
use RxThunder\Core\Router\RabbitMq\Adapter;
use RxThunder\Core\Router\Router;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class RabbitMqConsole extends AbstractConsole
{
    public static $expression = 'listen:broker:rabbit queue [connection] [--middlewares=]* [--timeout=]';
    public static $description = 'RabbitMq consumer to send command to saga process manager';
    public static $argumentsAndOptions = [
        'queue' => 'Name of the queue to connect to',
        'connection' => 'RabbitMq instance to connect to',
        '--timeout' => 'timeout',
    ];

    public static $defaults = [
        'timeout' => 10000,
    ];

    private $parameterBag;
    private $router;
    private $adapter;

    public function __construct(
        ParameterBagInterface $parameterBag,
        Router $router,
        Adapter $adapter
    ) {
        $this->parameterBag = $parameterBag;
        $this->router = $router;
        $this->adapter = $adapter;
    }

    public function __invoke(
        string $queue,
        string $connection,
        array $middlewares,
        int $timeout
    ) {
        // You only need to set the default scheduler once
        Scheduler::setDefaultFactory(
            function () {
                // The getLoop method auto start loop
                return new Scheduler\EventLoopScheduler(EventLoop::getLoop());
            }
        );

        // Middlewares declared in config/middleware.php
//        foreach ($middlewares as $middleware) {
//            $this->middlewareProvider->append($this->container->get("middleware.{$middleware}"));
//        }

        $bunny = new Client(EventLoop::getLoop(), [
            'host' => $this->parameterBag->get("rabbit.{$connection}.host"),
            'port' => $this->parameterBag->get("rabbit.{$connection}.port"),
            'vhost' => $this->parameterBag->get("rabbit.{$connection}.vhost"),
            'user' => $this->parameterBag->get("rabbit.{$connection}.user"),
            'password' => $this->parameterBag->get("rabbit.{$connection}.password"),
        ]);

//        $this->log->info('Connect to eventStore');
        $bunny
            ->consume($queue, 1)
            ->flatMap($this->adapter)
            ->subscribe(
                function ($subject) {
                    ($this->router)($subject);
                },
                function ($e) {
                    var_dump($e);
                }
            );

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
