<?php

use Symfony\Component\DependencyInjection\Definition;
use Th3Mouk\Thunder\Router\AbstractRoute;
use Th3Mouk\Thunder\Router\RoutePass;

$definition = new Definition();
$definition->setPublic(false);
$definition->setAutowired(true);
$definition->setAutoconfigured(true);

$this->registerClasses($definition, 'Th3Mouk\\Thunder\\Router\\', '../src/Router');

$container->registerForAutoconfiguration(AbstractRoute::class)
    ->addTag('route');

$container->addCompilerPass(new RoutePass());
