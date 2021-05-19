<?php
declare(strict_types = 1);

namespace Pinge\SDK\Stacktrace;

use JsonSerializable;

final class Context implements JsonSerializable
{
    /**
     * The line number.
     *
     * @var integer
     */
    private $line;

    /**
     * The line context.
     *
     * @var string
     */
    private $context;

    /**
     * Constructor
     * @param  integer $line    The line number.
     * @param  string  $context The line context.
     * @return void
     */
    public function __construct(int $line, string $context)
    {
        $this->line = $line;
        $this->context = $context;
    }

    /**
     * Get the line.
     *
     * @return integer
     */
    public function line(): int
    {
        return $this->line;
    }

    /**
     * Get the context.
     *
     * @return string
     */
    public function context(): string
    {
        return $this->context;
    }

    /**
     * Cast the object to string.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ['line' => $this->line(), 'context' => $this->context()];
    }
}
