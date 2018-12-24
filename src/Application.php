<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\Thunder;

use Silly\Application as BaseApplication;
use Silly\Input\InputOption;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Th3Mouk\Thunder\Kernel\KernelInterface;

class Application extends BaseApplication
{
    public function __construct(KernelInterface $kernel)
    {
        $kernelClass = \get_class($kernel);
        $kernel->boot();

        parent::__construct($kernelClass::NAME, $kernelClass::VERSION);
        $this->useContainer($kernel->getContainer());

        $this->getDefinition()->addOptions([
            new InputOption('--env', '-e', InputOption::VALUE_OPTIONAL, 'The environment name',
                $kernel->getEnvironment()),
        ]);

        /** @var ContainerBuilder $container */
        $container = $kernel->getContainer();

        $consoleServicesList = $container->findTaggedServiceIds('console');

        foreach ($consoleServicesList as $id => $tag) {
            $this->registerConsole($id);
        }
    }

    private function registerConsole(string $console)
    {
        $this
            ->command($console::$expression, $console)
            ->descriptions($console::$description, $console::$argumentsAndOptions)
            ->defaults($console::$defaults);
    }
}
