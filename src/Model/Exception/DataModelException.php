<?php

declare(strict_types=1);

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Model\Exception;

use RxThunder\Core\Model\DataModel;

class DataModelException extends \Exception
{
    private DataModel $data_model;

    public function __construct(DataModel $data_model, ?\Throwable $previous = null, string $message = '', int $code = 0)
    {
        parent::__construct($message, $code, $previous);
        $this->data_model = $data_model;
    }

    public function dataModel(): DataModel
    {
        return $this->data_model;
    }
}
