<?php

use RxThunder\Core\Console;

$container->registerForAutoconfiguration(Console::class)
    ->addTag('console');
