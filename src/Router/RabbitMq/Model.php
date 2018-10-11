<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\Thunder\Router\RabbitMq;

final class Model
{
    private $type;
    private $data;
    private $metadata;

    public function __construct($type, $data, $metadata)
    {
        $this->type = $type;
        $this->data = $data ?? [];
        $this->metadata = $metadata ?? [];
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
