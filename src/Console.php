<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core;

/**
 * @see https://github.com/mnapoli/silly/blob/master/docs/command-definition.md#command-definition
 */
abstract class Console
{
    public static string $expression = '';

    public static string $description = '';

    /** @var array<string, string> */
    public static array $arguments_and_options = [];

    /** @var array<string, bool|float|int|string> */
    public static array $defaults = [];
}
