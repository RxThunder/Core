<?php

use Symfony\Component\DependencyInjection\Definition;
use RxThunder\Core\Console\AbstractConsole;

$definition = new Definition();
$definition->setPublic(true);
$definition->setAutowired(true);
$definition->setAutoconfigured(true);

$container->registerForAutoconfiguration(AbstractConsole::class)
    ->addTag('console');

$this->registerClasses($definition, 'RxThunder\\Core\\Console\\', '../src/Console');
