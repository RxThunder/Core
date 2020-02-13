<?php

use Psr\Log\LoggerAwareInterface;
use Psr\Log\NullLogger;
use RxThunder\Core\Logger\LoggerPass;
use Symfony\Component\DependencyInjection\Definition;

$container->setDefinition('logger', new Definition(NullLogger::class));

$container
    ->registerForAutoconfiguration(LoggerAwareInterface::class)
    ->addTag(LoggerPass::TAG)
;

$container->addCompilerPass(new LoggerPass());
