<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Console;

/**
 * @see https://github.com/mnapoli/silly/blob/master/docs/command-definition.md#command-definition
 */
abstract class AbstractConsole
{
    /**
     * @var string
     */
    public static $expression = '';

    /**
     * @var string
     */
    public static $description = '';

    /**
     * @var iterable
     */
    public static $argumentsAndOptions = [];

    /**
     * @var iterable
     */
    public static $defaults = [];
}
