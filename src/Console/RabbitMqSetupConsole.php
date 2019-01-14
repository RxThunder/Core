<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Console;

use Bunny\Async\Client;
use Bunny\Channel;
use EventLoop\EventLoop;
use React\Promise\PromiseInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;

final class RabbitMqSetupConsole extends AbstractConsole
{
    public static $expression = 'rabbit:setup connection [path]';
    public static $description = 'Create queue and binding';

    public static $argumentsAndOptions = [
        'connection' => 'Name of the connections to use',
        'path' => 'Path where binding file are stored',
    ];

    public static $defaults = [
        'path' => '/external/rabbitmq',
    ];

    /** @var string */
    protected $path;

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct(
        ParameterBagInterface $parameterBag
    ) {
        $this->parameterBag = $parameterBag;
    }

    public function __invoke(string $connection, string $path)
    {
        $this->path = $path;

        $finder = new Finder();
        $finder->files()->in(
            sprintf(
                '%s%s/queues',
                $this->parameterBag->get('thunder.project_dir'),
                $this->path
            )
        );

        $bunny = new Client(EventLoop::getLoop(), [
            'host' => $this->parameterBag->get("rabbit.{$connection}.host"),
            'port' => $this->parameterBag->get("rabbit.{$connection}.port"),
            'vhost' => $this->parameterBag->get("rabbit.{$connection}.vhost"),
            'user' => $this->parameterBag->get("rabbit.{$connection}.user"),
            'password' => $this->parameterBag->get("rabbit.{$connection}.password"),
        ]);

        $promise = $bunny
            ->connect()
            ->then(
                function (Client $client) {
                    return $client->channel();
                }
            )
        ;

        foreach ($finder as $file) {
            $queue = $file->getBasename('.'.$file->getExtension());

            $promise = $promise->then(function (Channel $channel) use ($queue) {
                return $channel
                    ->queueDeclare($queue, false, true)
                    ->then(function () use ($channel) {
                        return $channel;
                    });
            });

            $routing_keys = json_decode($file->getContents(), true);

            foreach ($routing_keys as $routing_key => $exchanges) {
                foreach ($exchanges as $exchange) {
                    $promise = $this->bind($promise, $queue, $exchange, $routing_key);
                }
            }
        }

        $promise->done(function () {
            EventLoop::getLoop()->stop();
        });
    }

    private function bind(PromiseInterface $promise, string $queue, string $exchange, string $routing_key): PromiseInterface
    {
        return $promise->then(
            function (Channel $channel) use ($queue, $exchange, $routing_key) {
                return $channel
                    ->queueBind($queue, $exchange, $routing_key)
                    ->then(function () use ($channel) {
                        return $channel;
                    });
            }
        );
    }
}
