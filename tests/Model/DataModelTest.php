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

final class DataModelTest extends TestCase
{
    public function testDataModelConstruction(): void
    {
        $data_model = new DataModel('type');

        $this->assertSame('type', $data_model->type());
        $this->assertInstanceOf(Payload::class, $data_model->payload());
        $this->assertInstanceOf(MetadataBag::class, $data_model->metadata());

        $payload = new Payload(['test' => 'test']);

        $data_model = new DataModel('type', $payload, ['meta' => 'meta']);

        $this->assertSame('test', $data_model->payload()->dataInArrayFormat()['test']);
        $this->assertSame('meta', $data_model->metadata()->get('meta'));
    }
}
