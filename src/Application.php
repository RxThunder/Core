<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core;

use RxThunder\Core\Kernel\KernelInterface;
use Silly\Application as BaseApplication;
use Silly\Input\InputOption;

class Application extends BaseApplication
{
    public function __construct(KernelInterface $kernel)
    {
        $kernel_class = \get_class($kernel);
        $kernel->boot();

        parent::__construct($kernel_class::NAME, $kernel_class::VERSION);

        $container = $kernel->getContainer();
        $this->useContainer($container);

        $this->getDefinition()->addOptions([
            new InputOption(
                '--env',
                '-e',
                InputOption::VALUE_OPTIONAL,
                'The environment name',
                $kernel->getEnvironment()
            ),
        ]);

        $console_services_list = $container->findTaggedServiceIds('console');

        foreach ($console_services_list as $id => $tag) {
            $this->registerConsole($id);
        }
    }

    private function registerConsole(string $console_class): void
    {
        $this
            ->command($console_class::$expression, $console_class)
            ->descriptions($console_class::$description, $console_class::$arguments_and_options)
            ->defaults($console_class::$defaults);
    }
}
