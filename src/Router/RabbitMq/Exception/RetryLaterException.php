<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router\RabbitMq\Exception;

use Throwable;

class RetryLaterException extends \Exception
{
    /**
     * The delay to wait before retrying the message (milliseconds).
     *
     * @var int
     */
    private $delay;

    public function __construct(string $message = '', int $code = 0, Throwable $previous = null, int $delay = 2 * 60 * 1000)
    {
        parent::__construct($message, $code, $previous);
        $this->delay = $delay;
    }

    public function getDelay()
    {
        return $this->delay;
    }
}
