<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Core\Router\EventStore;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Rx\Observable;
use Rxnet\EventStore\Record\AcknowledgeableEventRecord;
use RxThunder\Core\Router\DataModel;
use RxThunder\Core\Router\Payload;

final class Adapter implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private $timeout = 5000;

    public function __invoke(AcknowledgeableEventRecord $record)
    {
//        $this->logger->info("received {$record->getType()} with {$record->getNumber()}@{$record->getStreamId()}");

        $metadata = [
            'uid' => $record->getId(),
            'stream_id' => $record->getStreamId(),
            'stream' => $record->getNumber().'@'.$record->getStreamId(),
            'date' => $record->getCreated(),
            'metadata' => $record->getMetadata(),
        ];

        $payload = new Payload($record->getData());
        $dataModel = new DataModel(
            $record->getType(),
            $payload,
            $metadata
        );
        $subject = new Subject($dataModel, $record);

        $subjectObs = $subject->skip(1)->share();

//        $subjectObs->subscribe($this->logger);

        $subjectObs
            // Give only x ms to execute
            ->timeout($this->timeout)
            ->subscribe(
                null,
                // Return exception from the code
                function (\Throwable $e) use ($record) {
//                        if ($e instanceof AcceptableException) {
//                            await($record->nack($record::NACK_ACTION_SKIP));
//                            $this->logger->debug("[nack-skip] Acceptable exception: {$e->getMessage()}");
//
//                            return;
//                        }

                    $record->nack($record::NACK_ACTION_SKIP)->subscribe(
                        null,
                        null,
                        function () use ($e, $record) {
                            echo "Message {$record->getType()} has been nack".PHP_EOL;
                            echo "Reason: {$e->getMessage()}".PHP_EOL;
                            echo "This occurs in {$e->getFile()} @line {$e->getLine()}".PHP_EOL;
                            $this->logger->error($e->getMessage(), [
                                'exception' => $e,
                            ]);
                        }
                    );
//                        $this->logger->debug("[nack-stop] Error {$e->getMessage()}");
                },
                function () use ($record) {
                    $record->ack()->subscribe(
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
