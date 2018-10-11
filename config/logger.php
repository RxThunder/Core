<?php

use Psr\Log\LoggerAwareInterface;
use Symfony\Component\DependencyInjection\Reference;

$container->registerForAutoconfiguration(LoggerAwareInterface::class)
    ->addMethodCall('setLogger', array(new Reference('logger')));
