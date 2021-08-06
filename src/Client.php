<?php
declare(strict_types = 1);

namespace Pinge\SDK;

use Throwable;
use Pinge\SDK\Transport\TransportContract;

final class Client
{
    /**
     * The client version.
     *
     * @var string
     */
    public const VERSION = '0.0.2';

    /**
     * The DSN object.
     *
     * @var \Pinge\SDK\Dsn
     */
    private $dsn;

    /**
     * The transport object.
     *
     * @var \Pinge\SDK\Transport\TransportContract
     */
    private $transport;

    /**
     * Constructor
     * @param  \Pinge\SDK\Dsn                         $dsn       The DSN object.
     * @param  \Pinge\SDK\Transport\TransportContract $transport The transport object.
     * @return void
     */
    public function __construct(Dsn $dsn, TransportContract $transport)
    {
        $this->dsn = $dsn;
        $this->transport = $transport;
    }

    /**
     * Capture an exception and send it to the API.
     *
     * @param  \Throwable $exception The exception object.
     * @return \Pinge\SDK\Event|null
     */
    public function captureException(Throwable $exception): ?Event
    {
        return $this->transport->usingDsn($this->dsn)->send($event = new Event($exception))
            ? $event
            : null;
    }
}
