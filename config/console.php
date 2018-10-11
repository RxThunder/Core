<?php

use Symfony\Component\DependencyInjection\Definition;
use Th3Mouk\Thunder\Console\AbstractConsole;

$definition = new Definition();
$definition->setPublic(true);
$definition->setAutowired(true);

$container->registerForAutoconfiguration(AbstractConsole::class)
    ->addTag('console');

$this->registerClasses($definition, 'Th3Mouk\\Thunder\\Console\\', '../src/Console', '../src/Console/{Application.php}');
