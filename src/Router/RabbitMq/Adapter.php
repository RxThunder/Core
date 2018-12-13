<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\Thunder\Router\RabbitMq;

use Rx\Observable;
use Rxnet\RabbitMq\Message;
use Th3Mouk\Thunder\Router\DataModel;

final class Adapter
{
    private $timeout = 5000;

    public function __invoke(Message $message)
    {
        $message->getData();
        $type = $message->getRoutingKey();
        $data = $message->getData();

//        $this->logger->info("received {$record->getType()} with {$record->getNumber()}@{$record->getStreamId()}");

        $metadata = [
            'uid' => $message->consumerTag,
            'stream_id' => $message->deliveryTag,
            'stream' => $message->redelivered,
            'date' => $message->exchange,
        ];

        $dataModel = new DataModel($type, $data, $metadata);
        $subject = new Subject($dataModel, $message);

        $subjectObs = $subject->skip(1)->share();

//        $subjectObs->subscribe($this->logger);

        $subjectObs
            // Give only x ms to execute
            ->timeout($this->timeout)
            ->subscribe(
                null,
                // Return exception from the code
                function (\Exception $e) use ($message) {
//                        if ($e instanceof AcceptableException) {
//                            await($record->nack($record::NACK_ACTION_SKIP));
//                            $this->logger->debug("[nack-skip] Acceptable exception: {$e->getMessage()}");
//
//                            return;
//                        }
                    $message->nack()->subscribe(
                        null,
                        null,
                        function () use ($message) {echo "nack complete {$message->getRoutingKey()}".PHP_EOL; }
                    );
//                        $this->logger->debug("[nack-stop] Error {$e->getMessage()}");
                },
                function () use ($message) {
                    $message->ack()->subscribe(
                        null,
                        null,
                        function () {echo 'ack complete'.PHP_EOL; }
                    );
//                        $this->logger->debug('[ack] Completed');
                }
            );

        return Observable::just($subject);
    }
}
