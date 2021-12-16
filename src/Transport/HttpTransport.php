<?php
declare(strict_types = 1);

namespace Pinge\SDK\Transport;

use Pinge\SDK\Dsn;
use Pinge\SDK\Event;
use Pinge\SDK\Client;

final class HttpTransport implements TransportContract
{
    /**
     * The DSN object.
     *
     * @var \Pinge\SDK\Dsn|null
     */
    private $dsn;

    /**
     * Send the event.
     *
     * @param  \Pinge\SDK\Event $event The event object.
     * @return boolean
     */
    public function send(Event $event): bool
    {
        $handle = curl_init((string) $this->dsn);
        curl_setopt_array($handle, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'event_id' => (string) $event->eventId(),
                'message' => $event->message(),
                'exception' => $event->exception(),
                'stacktrace' => $event->stacktrace(),
                'environment' => $event->environment(),
                'timestamp' => $event->timestamp(),
                'sdk_version' => Client::VERSION,

                'os' => $event->os(),
                'runtime' => $event->runtime(),
                'server' => $event->server(),
                'url' => $event->url(),
            ]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ]);

        if (!curl_exec($handle)) {
            curl_close($handle);
            return false;
        }

        $status = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if (!in_array($status, [200, 201, 204])) {
            curl_close($handle);
            return false;
        }

        curl_close($handle);

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
        $this->dsn = $dsn;
        return $this;
    }
}
