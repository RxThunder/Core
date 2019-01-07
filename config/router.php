<?php

use Symfony\Component\DependencyInjection\Definition;
use RxThunder\Core\Router\AbstractRoute;
use RxThunder\Core\Router\RoutePass;

$definition = new Definition();
$definition->setPublic(false);
$definition->setAutowired(true);
$definition->setAutoconfigured(true);

$this->registerClasses($definition, 'RxThunder\\Core\\Router\\', '../src/Router');

$container->registerForAutoconfiguration(AbstractRoute::class)
    ->addTag('route');

$container->addCompilerPass(new RoutePass());
