<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DependencyInjection\Reference;

$container
    ->autowire(ContainerBag::class, ContainerBag::class)
    ->addArgument(new Reference(ContainerInterface::class));

$container->setAlias(ParameterBagInterface::class, ContainerBag::class);
