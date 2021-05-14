<?php
declare(strict_types = 1);

namespace Pinge\SDK\Stacktrace;

use Throwable;
use InvalidArgumentException;
use UnexpectedValueException;
use Pinge\SDK\Stacktrace\Frame;

final class Stacktrace
{
    /**
     * The frame list.
     *
     * @var array
     */
    private $frames = [];

    /**
     * Constructor
     *
     * @param  array $frames The frame list.
     * @return void
     *
     * @throws \InvalidArgumentException Thrown when no frames have been passed.
     * @throws \UnexpectedValueException Thrown when one or more frames are of an invalid type.
     */
    public function __construct(array $frames)
    {
        if (!count($frames)) {
            throw new InvalidArgumentException('Frame list must not be empty.');
        }

        foreach ($frames as $frame) {
            if (!($frame instanceof Frame)) {
                throw new UnexpectedValueException('Every frame must be an instance of \Pinge\SDK\Stracktrace\Frame.');
            }
        }

        $this->frames = $frames;
    }

    /**
     * Get the frame list.
     *
     * @return array
     */
    public function frames(): array
    {
        return $this->frames;
    }

    /**
     * Cast the object to a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->frames);
    }

    /**
     * Create a stacktrace based on an exception.
     *
     * @param  \Throwable $exception The exception object.
     * @return \Pinge\SDk\Stacktrace\Stacktrace
     */
    public static function createFromException(Throwable $exception): Stacktrace
    {
        $originatingFrame = [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'function' => '',
        ];

        return new self(array_map(function ($trace): Frame {
            return Frame::createFromTrace($trace);
        }, [$originatingFrame, ...$exception->getTrace()]));
    }
}
