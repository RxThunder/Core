<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router\EventStore;

use Rxnet\EventStore\Record\AcknowledgeableEventRecord;
use RxThunder\Core\Router\AbstractSubject;
use RxThunder\Core\Router\DataModel;

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
