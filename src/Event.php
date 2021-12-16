<?php
declare(strict_types = 1);

namespace Pinge\SDK;

use Throwable;
use Pinge\SDK\Stacktrace\Stacktrace;

final class Event
{
    /**
     * The event id.
     *
     * @var \Pinge\SDK\EventId
     */
    private $eventId;

    /**
     * The event timestamp.
     *
     * @var float|null
     */
    private $timestamp;

    /**
     * The environment.
     *
     * @var string
     */
    private $environment;

    /**
     * The exception object.
     *
     * @var \Throwable
     */
    private $exception;

    /**
     * The current os.
     *
     * @var string
     */
    private $os;

    /**
     * The server name.
     *
     * @var string|null
     */
    private $server;

    /**
     * The runtime.
     *
     * @var string
     */
    private $runtime;

    /**
     * The current URL.
     *
     * @var string|null
     */
    private $url;

    /**
     * Constructor
     *
     * @param  \Throwable $exception The exception object.
     * @return void
     */
    public function __construct(Throwable $exception)
    {
        $this->timestamp = microtime(true);
        $this->exception = $exception;
        $this->eventId = EventId::createFromException($exception);
        $this->os = PHP_OS;
        $this->server = $_SERVER['SERVER_NAME'] ?? null;
        $this->runtime = 'PHP ' . phpversion();
        $this->url = $_SERVER['REQUEST_URI'] ?? null;
    }

    /**
     * Get the event id.
     *
     * @return \Pinge\SDK\EventId
     */
    public function eventId(): EventId
    {
        return $this->eventId;
    }

    /**
     * Get the timestamp.
     *
     * @return float
     */
    public function timestamp(): float
    {
        return $this->timestamp;
    }

    /**
     * Get the environment.
     *
     * @return string
     */
    public function environment(): string
    {
        return $this->environment ?? 'production';
    }

    /**
     * Get the event exception class.
     *
     * @return string
     */
    public function exception(): string
    {
        return get_class($this->exception);
    }

    /**
     * Get the event message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->exception->getMessage();
    }

    /**
     * Get the event stracktrace.
     *
     * @return \Pinge\SDK\Stacktrace\Stacktrace|null
     */
    public function stacktrace(): ?Stacktrace
    {
        return count($this->exception->getTrace())
            ? Stacktrace::createFromException($this->exception)
            : null;
    }

    /**
     * Get the os.
     *
     * @return string
     */
    public function os(): string
    {
        return $this->os;
    }

    /**
     * Get the server.
     *
     * @return null|string
     */
    public function server(): ?string
    {
        return $this->server;
    }

    /**
     * Get the runtime.
     *
     * @return string
     */
    public function runtime(): string
    {
        return $this->runtime;
    }

    /**
     * Get the current url if any.
     *
     * @return null|string
     */
    public function url(): ?string
    {
        return $this->url;
    }
}
