<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router;

use Rx\Subject\Subject;

class AbstractSubject extends Subject
{
    /**
     * @var DataModel
     */
    protected $dataModel;

    public function __construct($dataModel)
    {
        $this->dataModel = $dataModel;
    }

    public function getDataModel(): DataModel
    {
        return $this->dataModel;
    }

    public function getRoutingPath()
    {
        return $this->dataModel->getType();
    }
}
