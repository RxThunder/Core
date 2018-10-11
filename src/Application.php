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
use Th3Mouk\Thunder\Console\EventStoreConsole;
use Th3Mouk\Thunder\Console\RabbitMqConsole;
use Th3Mouk\Thunder\Kernel\KernelInterface;

class Application extends BaseApplication
{
    public function __construct(KernelInterface $kernel)
    {
        $kernelClass = \get_class($kernel);
        $kernel->boot();

        parent::__construct($kernel::NAME, $kernelClass::VERSION);
        $this->useContainer($kernel->getContainer());

        $this->getDefinition()->addOptions([
            new InputOption('--env', '-e', InputOption::VALUE_OPTIONAL, 'The environment name',
                $kernel->getEnvironment()),
        ]);

        // todo add console discovery

        $this
            ->command(
                EventStoreConsole::$expression,
                EventStoreConsole::class
            )
            ->descriptions(
                EventStoreConsole::$description,
                EventStoreConsole::$argumentsAndOptions
            )
            ->defaults(EventStoreConsole::$defaults)
        ;

        $this
            ->command(
                RabbitMqConsole::$expression,
                RabbitMqConsole::class
            )
            ->descriptions(
                RabbitMqConsole::$description,
                RabbitMqConsole::$argumentsAndOptions
            )
            ->defaults(RabbitMqConsole::$defaults)
        ;
    }

//    public function doRun(InputInterface $input, OutputInterface $output)
//    {
//        $this->registerCommands();
//
//        return parent::doRun($input, $output);
//    }
//
//    public function registerCommands()
//    {
//        foreach (($this->consoleDiscovery)() as $className) {
//            try {
//                $r = new \ReflectionClass($className);
//            } catch (ReflectionException $e) {
//                continue;
//            }
//
//            if (!$r->isSubclassOf(ConsoleInterface::class) || $r->isAbstract()) {
//                continue;
//            }
//
//            $this
//                ->command(
//                    $r->getStaticPropertyValue('expression'),
//                    $r->getName()
//                )
//                ->descriptions(
//                    $r->getStaticPropertyValue('description'),
//                    $r->getStaticPropertyValue('argumentsAndOptions')
//                )
//                ->defaults($r->getStaticPropertyValue('defaults'));
//        }
//    }
}
