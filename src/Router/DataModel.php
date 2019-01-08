<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router;

final class DataModel
{
    private $type;
    private $payload;
    private $metadata;

    public function __construct(
        string $type,
        Payload $payload = null,
        ?array $metadata = []
    ) {
        $this->type = $type;
        $this->payload = $payload ?? new Payload();
        $this->metadata = $metadata;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPayload(): Payload
    {
        return $this->payload;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
