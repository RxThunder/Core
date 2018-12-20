<?php

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DependencyInjection\Reference;

$container
    ->autowire(ContainerBag::class, ContainerBag::class)
    ->addArgument(new Reference(\Psr\Container\ContainerInterface::class));

$container->setAlias(ParameterBagInterface::class, ContainerBag::class);
