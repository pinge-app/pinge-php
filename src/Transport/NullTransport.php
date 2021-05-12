<?php
declare(strict_types = 1);

namespace Pinge\SDK\Transport;

use Pinge\SDK\Dsn;
use Pinge\SDK\Event;

final class NullTransport implements TransportContract
{
    /**
     * Send the event.
     *
     * @param  \Pinge\SDK\Event $event The event object.
     * @return boolean
     */
    public function send(Event $event): bool
    {
        return true;
    }

    /**
     * Set the DSN.
     *
     * @param  \Pinge\SDK\Dsn $dsn The DSN object.
     * @return self
     */
    public function usingDsn(Dsn $dsn): self
    {
        return $this;
    }
}
