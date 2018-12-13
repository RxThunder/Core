<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\Thunder\Router\EventStore;

use Rxnet\EventStore\AcknowledgeableEventRecord;
use Th3Mouk\Thunder\Router\AbstractSubject;
use Th3Mouk\Thunder\Router\DataModel;

final class Subject extends AbstractSubject
{
    private $eventRecord;

    public function __construct(
        DataModel $dataModel,
        AcknowledgeableEventRecord $eventRecord
    ) {
        $this->eventRecord = $eventRecord;
        parent::__construct($dataModel);
    }
}
