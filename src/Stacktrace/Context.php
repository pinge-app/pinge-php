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
     * The line content.
     *
     * @var string
     */
    private $content;

    /**
     * Constructor
     * @param  integer $line    The line number.
     * @param  string  $content The line content.
     * @return void
     */
    public function __construct(int $line, string $content)
    {
        $this->line = $line;
        $this->content = $content;
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
     * Get the content.
     *
     * @return string
     */
    public function content(): string
    {
        return $this->content;
    }

    /**
     * Cast the object to string.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ['line' => $this->line(), 'content' => $this->content()];
    }
}
