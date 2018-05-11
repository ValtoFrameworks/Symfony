<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Messenger;

/**
 * @author Samuel Roze <samuel.roze@gmail.com>
 */
class TraceableMessageBus implements MessageBusInterface
{
    private $decoratedBus;
    private $dispatchedMessages = array();

    public function __construct(MessageBusInterface $decoratedBus)
    {
        $this->decoratedBus = $decoratedBus;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch($message)
    {
        try {
            $result = $this->decoratedBus->dispatch($message);

            $this->dispatchedMessages[] = array(
                'message' => $message,
                'result' => $result,
            );

            return $result;
        } catch (\Throwable $e) {
            $this->dispatchedMessages[] = array(
                'message' => $message,
                'exception' => $e,
            );

            throw $e;
        }
    }

    public function getDispatchedMessages(): array
    {
        return $this->dispatchedMessages;
    }

    public function reset()
    {
        $this->dispatchedMessages = array();
    }
}
