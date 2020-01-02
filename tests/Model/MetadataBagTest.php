<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Model;

use PHPUnit\Framework\TestCase;

final class MetadataBagTest extends TestCase
{
    public function testConstructionAndUsage(): void
    {
        $metadata_bag = new MetadataBag([
            true => true,
            2 => 2,
            'string' => 'string',
        ]);

        $this->assertSame(true, $metadata_bag->get('1'));
        $this->assertSame(2, $metadata_bag->get('2'));
        $this->assertSame('string', $metadata_bag->get('string'));
        $this->assertSame(null, $metadata_bag->get('null'));
    }

    public function testAddingNonScalarThrow(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new MetadataBag([
            'object' => new \stdClass(),
        ]);
    }
}
