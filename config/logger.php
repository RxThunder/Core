<?php

use Psr\Log\LoggerAwareInterface;
use Symfony\Component\DependencyInjection\Reference;

if ($container->hasDefinition('logger')) {
    $class = new Reference('logger');
} else {
    $class = new \Psr\Log\NullLogger();
}

$container
    ->registerForAutoconfiguration(LoggerAwareInterface::class)
    ->addMethodCall('setLogger', [$class])
;
