<?php
declare(strict_types = 1);

namespace Pinge\SDK;

use Throwable;

final class EventId
{
    /**
     * The event id.
     *
     * @var string
     */
    private $id;

    /**
     * Constructor
     *
     * @param  string $id The event id.
     * @return void
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * Cast the object to string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->id;
    }

    /**
     * Create an event id object using an exception.
     *
     * @param  \Throwable $exception The exception object.
     * @return \Pinge\SDK\EventId
     */
    public static function createFromException(Throwable $exception): self
    {
        return new self(hash(
            'crc32',
            sprintf(
                '%s|%s|%s',
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            )
        ));
    }
}
