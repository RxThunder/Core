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
use RxThunder\Core\Router\RabbitMq\Exception\AcceptableException;
use RxThunder\Core\Router\RabbitMq\Exception\RejectException;
use RxThunder\Core\Router\RabbitMq\Exception\RetryLaterException;

final class Adapter implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var int|null
     */
    private $timeout;
    /**
     * @var bool
     */
    private $rejectToBottom;
    /**
     * @var string|null
     */
    private $delayedExchangeName;

    public function __construct()
    {
        $this->timeout = null;
        $this->rejectToBottom = false;
        $this->delayedExchangeName = null;
    }

    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;
    }

    public function setDelayedExchangeName(string $name)
    {
        $this->delayedExchangeName = $name;
    }

    public function rejectToBottomInsteadOfNacking()
    {
        $this->rejectToBottom = true;
    }

    public function __invoke(Message $message)
    {
//        $this->logger->info("received {$record->getType()} with {$record->getNumber()}@{$record->getStreamId()}");

        $metadata = [
            'uid' => $message->consumerTag,
            'stream_id' => $message->deliveryTag,
            'stream' => $message->redelivered,
            'date' => $message->exchange,
            'headers' => $message->headers,
        ];

        $payload = new Payload($message->getData());
        if (Constants::DATA_FORMAT_JSON === $message->getHeader(Headers::CONTENT_TYPE)) {
            if (\is_array($arrayData = json_decode($message->getData(), true))) {
                $payload = new Payload($arrayData);
            }
        }

        $dataModel = new DataModel(
            $message->getRoutingKey(),
            $payload,
            $metadata
        );
        $subject = new Subject($dataModel, $message);

        $subjectObs = $subject->skip(1)->share();

//        $subjectObs->subscribe($this->logger);

        if (null !== $this->timeout) {
            // Give only x ms to execute
            $subjectObs = $subjectObs->timeout($this->timeout);
        }

        $subjectObs
            ->subscribe(
                null,
                // Return exception from the code
                function (\Throwable $e) use ($message, $dataModel) {
                    if ($e instanceof AcceptableException) {
                        $this->handleAcceptableException($message, $dataModel, $e);

                        return;
                    }

                    if ($e instanceof RejectException) {
                        $this->handleRejectException($message, $e);

                        return;
                    }

                    if ($e instanceof RetryLaterException) {
                        $this->handleRetryLaterException($message, $e);

                        return;
                    }

                    $this->handleException($message, $e);
                // $this->logger->debug("[nack-stop] Error {$e->getMessage()}");
                },
                function () use ($message) {
                    $date = new \DateTimeImmutable();

                    $message->ack()->subscribe(
                        null,
                        null,
                        function () use ($message, $date) {
                            echo $date->format(DATE_ISO8601).' > '.$message->getRoutingKey().' > ack'.PHP_EOL;
                        }
                    );
                    // $this->logger->debug('[ack] Completed');
                }
            );

        return Observable::of($subject);
    }

    private function handleAcceptableException(Message $message, DataModel $dataModel, AcceptableException $e): void
    {
        $message->ack()->subscribe(
            null,
            null,
            function () use ($e, $dataModel, $message) {
                echo "ack but due to acceptable exception {$message->getRoutingKey()}".PHP_EOL;

                if ($previous = $e->getPrevious()) {
                    $this->logger->warning($previous->getMessage(), [
                        'exception' => $previous,
                        'routing_key' => $dataModel->getType(),
                        'payload' => $message->getData(),
                        'metadata' => $dataModel->getMetadata(),
                    ]);
                }
            }
        );
    }

    private function handleException(Message $message, \Throwable $e): void
    {
        $onCompleted = function () use ($e, $message) {
            echo "Message {$message->getRoutingKey()} has been nack".PHP_EOL;
            echo "Reason: {$e->getMessage()}".PHP_EOL;
            echo "This occurs in {$e->getFile()} @line {$e->getLine()}".PHP_EOL;
            $this->logger->error($e->getMessage(), [
                'exception' => $e,
            ]);
        };

        if ($this->rejectToBottom) {
            $message->rejectToBottom()->subscribe(null, null, $onCompleted);
        } else {
            $message->nack()->subscribe(null, null, $onCompleted);
        }
    }

    private function handleRejectException(Message $message, RejectException $e): void
    {
        $message->reject(false)->subscribe(
            null,
            null,
            function () use ($e, $message) {
                echo "Message {$message->getRoutingKey()} has been rejected".PHP_EOL;
                echo "Reason: {$e->getMessage()}".PHP_EOL;
                echo "This occurs in {$e->getFile()} @line {$e->getLine()}".PHP_EOL;
                $this->logger->error($e->getMessage(), [
                    'exception' => $e,
                ]);
            }
        );
    }

    private function handleRetryLaterException(Message $message, RetryLaterException $e)
    {
        if (null === $this->delayedExchangeName) {
            throw new \Exception('You cannot use the RetryLaterException without a delayedExchange name');
        }

        $message->retryLater($e->getDelay(), $this->delayedExchangeName)->subscribe(
            null,
            null,
            function () use ($e, $message) {
                echo "Message {$message->getRoutingKey()} has been requeued for later".PHP_EOL;
                echo "Reason: {$e->getMessage()}".PHP_EOL;
                echo "This occurs in {$e->getFile()} @line {$e->getLine()}".PHP_EOL;
                $this->logger->warning($e->getMessage(), [
                    'exception' => $e,
                ]);
            }
        );
    }
}
