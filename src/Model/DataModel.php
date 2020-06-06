<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Model;

final class DataModel
{
    private string $type;
    private Payload $payload;
    private MetadataBag $metadata;

    /**
     * @psalm-param array<string, scalar> $metadata
     */
    public function __construct(
        string $type,
        ?Payload $payload = null,
        array $metadata = []
    ) {
        $this->type     = $type;
        $this->payload  = $payload ?? new Payload();
        $this->metadata = new MetadataBag($metadata);
    }

    public function type(): string
    {
        return $this->type;
    }

    public function payload(): Payload
    {
        return $this->payload;
    }

    public function metadata(): MetadataBag
    {
        return $this->metadata;
    }
}
