<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\Thunder\Console;

use EventLoop\EventLoop;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Rx\Observer\CallbackObserver;
use Rxnet\EventStore\EventStore;
use Rxnet\EventStore\Exception\NotMasterException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Th3Mouk\Thunder\Router\AbstractSubject;
use Th3Mouk\Thunder\Router\EventStore\Adapter;
use Th3Mouk\Thunder\Router\Router;

final class EventStoreConsole extends AbstractConsole implements LoggerAwareInterface
{
    use LoggerAwareTrait;

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

    private $eventStore;
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
        string $stream,
        string $group,
        array $middlewares,
        int $timeout
    ) {
        $this->eventStore = new EventStore(EventLoop::getLoop());

        $dsn = $this->parameterBag->get('eventstore.tcp');

        $router = new CallbackObserver(
            function (AbstractSubject $subject) {
                try {
                    ($this->router)($subject);
                } catch (\Throwable $throwable) {
                    $this->logger->error($throwable);
                }
            },
            function (\Throwable $throwable) {$this->logger->error($throwable); }
        );

        $connection = function ($dsn) use ($stream, $group) {
            $this->eventStore = new EventStore(EventLoop::getLoop());

            return $this->eventStore
                ->connect($dsn)
                ->flatMapTo($this->eventStore->persistentSubscription($stream, $group));
        };

        $reconnect = function (\Exception $e) use ($connection, $dsn) {
            if ($e instanceof NotMasterException) {
                $credentials = parse_url($dsn);
                $dsn = $credentials['user'].':'.$credentials['pass'].'@'.$e->getMasterIp().':'.$e->getMasterPort();

                return $connection($dsn);
            }
            throw $e;
        };

        $connection($dsn)
            ->catch($reconnect)
            ->flatMap($this->adapter)
            ->subscribe($router)
        ;

        EventLoop::getLoop()->run();
    }
}
