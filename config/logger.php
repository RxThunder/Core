<?php

use Psr\Log\LoggerAwareInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\Reference;

if ($container->hasDefinition('logger')) {
    $class = new Reference('logger');
} else {
    $class = new NullLogger();
}

$container
    ->registerForAutoconfiguration(LoggerAwareInterface::class)
    ->addMethodCall('setLogger', [$class])
;
