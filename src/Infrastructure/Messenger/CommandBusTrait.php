<?php

declare(strict_types=1);

namespace App\Infrastructure\Messenger;


use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

trait CommandBusTrait
{
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $_commandBus;

    /**
     * @param $command
     *
     * @return mixed
     */
    public function dispatchCommand($command)
    {
        $envelope = $this->_commandBus->dispatch($command);

        $handledStamp = $envelope->last(HandledStamp::class);

        /** @noinspection NullPointerExceptionInspection */
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        return $handledStamp->getResult();
    }
}