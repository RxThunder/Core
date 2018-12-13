<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\Thunder\Router\EventStore;

use Rx\Observable;
use Rxnet\EventStore\AcknowledgeableEventRecord;
use Th3Mouk\Thunder\Router\DataModel;

final class Adapter
{
    private $timeout = 5000;

    public function __invoke(AcknowledgeableEventRecord $record)
    {
        $type = $record->getType();
        $data = $record->getData();
        $meta = $record->getMetadata();

//        $this->logger->info("received {$record->getType()} with {$record->getNumber()}@{$record->getStreamId()}");

        $metadata = [
            'uid' => $record->getId(),
            'stream_id' => $record->getStreamId(),
            'stream' => $record->getNumber().'@'.$record->getStreamId(),
            'date' => $record->getCreated(),
            'metadata' => $meta,
        ];

        $dataModel = new DataModel($type, $data, $metadata);
        $subject = new Subject($dataModel, $record);

        $subjectObs = $subject->skip(1)->share();

//        $subjectObs->subscribe($this->logger);

        $subjectObs
            // Give only x ms to execute
            ->timeout($this->timeout)
            ->subscribe(
                null,
                // Return exception from the code
                function (\Exception $e) use ($record) {
//                        if ($e instanceof AcceptableException) {
//                            await($record->nack($record::NACK_ACTION_SKIP));
//                            $this->logger->debug("[nack-skip] Acceptable exception: {$e->getMessage()}");
//
//                            return;
//                        }
                    $record->nack($record::NACK_ACTION_SKIP)->subscribe(
                        null,
                        null,
                        function () use ($record) {echo "nack complete {$record->getNumber()}".PHP_EOL; }
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
