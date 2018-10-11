<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\Thunder\Router\RabbitMq;

use Rxnet\RabbitMq\Message;
use Th3Mouk\Thunder\Router\AbstractSubject;

final class Subject extends AbstractSubject
{
    private $model;
    private $message;

    public function __construct(
        Model $model,
        Message $message
    ) {
        $this->model = $model;
        $this->message = $message;
    }

    public function getRoutingPath()
    {
        return $this->model->getType();
    }
}
