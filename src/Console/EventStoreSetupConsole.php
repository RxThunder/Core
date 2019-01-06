<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\Thunder\Console;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;

final class EventStoreSetupConsole extends AbstractConsole
{
    public static $expression = 'eventstore:setup [path]';
    public static $description = 'Add all projections and subscriptions';

    public static $argumentsAndOptions = [
        'path' => 'Path where projections file are stored',
    ];

    public static $defaults = [
        'path' => '/external/eventstore',
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

    public function __invoke(string $path)
    {
        $this->path = $path;
        $this->createContinuousProjections();
        $this->createPersistentSubscriptions();
    }

    private function createContinuousProjections()
    {
        $client = new \GuzzleHttp\Client();

        $finder = new Finder();
        $finder->files()->in(
            sprintf(
                '%s%s/projections',
                $this->parameterBag->get('thunder.project_dir'),
                $this->path
            )
        );

        foreach ($finder as $file) {
            $request = new Request(
                'POST',
                sprintf(
                    '%s/projections/continuous?name=%s&type=%s&enabled=%s&emit=%s&trackemittedstreams=%s',
                    $this->parameterBag->get('eventstore.http'),
                    $file->getBasename('.'.$file->getExtension()),
                    'JS',
                    'true',
                    'true',
                    'true'
                ),
                [
                    'Content-Type' => 'application/json',
                ],
                $file->getContents()
            );

            try {
                $client->send($request);
            } catch (GuzzleException $e) {
                switch ($e->getCode()) {
                case 409:
                    break;
                default:
                    var_dump($e);
                }
            }
        }
    }

    private function createPersistentSubscriptions()
    {
        $client = new \GuzzleHttp\Client();

        $path = sprintf(
            '%s%s/persistentSubscriptions.json',
            $this->parameterBag->get('thunder.project_dir'),
            $this->path
        );

        if (!file_exists($path)) {
            return;
        }

        if (!$content = file_get_contents($path)) {
            return;
        }

        $streams = json_decode($content, true);

        foreach ($streams as $stream => $groups) {
            foreach ($groups as $group => $options) {
                if (!$options = json_encode($options)) {
                    return;
                }

                $request = new Request(
                    'PUT',
                    sprintf(
                        '%s/subscriptions/%s/%s',
                        $this->parameterBag->get('eventstore.http'),
                        $stream,
                        $group
                    ),
                    [
                        'Content-Type' => 'application/json',
                    ],
                    $options
                );

                try {
                    $client->send($request);
                } catch (GuzzleException $e) {
                    switch ($e->getCode()) {
                        case 409:
                            break;
                        default:
                            var_dump($e);
                    }
                }
            }
        }
    }
}
