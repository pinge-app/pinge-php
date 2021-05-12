<?php
declare(strict_types = 1);

namespace Pinge\SDK;

use Throwable;

final class Event
{
    /**
     * The calculated event id.
     *
     * @var string
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
     * The event name.
     *
     * @var string
     */
    private $name;

    /**
     * The exception object.
     *
     * @var \Throwable
     */
    private $exception;

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
        $this->eventId = $this->calculateEventId($exception);
    }

    /**
     * Get the event id.
     *
     * @return string
     */
    public function eventId(): string
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
     * Get the event type.
     *
     * @return string
     */
    public function type(): string
    {
        return get_class($this->exception);
    }

    /**
     * Get the event name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->exception->getMessage();
    }

    /**
     * Get the event stracktrace.
     *
     * @return array
     */
    public function stacktrace(): array
    {
        return $this->exception->getTrace();
    }

    /**
     * Calculate the exception event id.
     *
     * @param  \Throwable $exception The exception object.
     * @return string|false
     */
    private function calculateEventId(Throwable $exception)
    {
        return hash(
            'crc32',
            sprintf(
                '%s|%s|%s',
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            )
        );
    }
}
