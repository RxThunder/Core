<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router\RabbitMq;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Rx\Observable;
use Rxnet\RabbitMq\Message;
use RxThunder\Core\Router\DataModel;
use RxThunder\Core\Router\Payload;

final class Adapter implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private $timeout = 5000;

    public function __invoke(Message $message)
    {
//        $this->logger->info("received {$record->getType()} with {$record->getNumber()}@{$record->getStreamId()}");

        $metadata = [
            'uid' => $message->consumerTag,
            'stream_id' => $message->deliveryTag,
            'stream' => $message->redelivered,
            'date' => $message->exchange,
        ];

        $payload = new Payload($message->getData());
        $dataModel = new DataModel(
            $message->getRoutingKey(),
            $payload,
            $metadata
        );
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
                        function () use ($e, $message) {
                            echo "nack complete {$message->getRoutingKey()}".PHP_EOL;
                            $this->logger->error($e->getMessage(), [
                                'exception' => $e,
                            ]);
                        }
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
