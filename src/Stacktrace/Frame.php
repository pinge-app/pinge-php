<?php
declare(strict_types = 1);

namespace Pinge\SDK\Stacktrace;

use SplFileObject;
use JsonSerializable;

final class Frame implements JsonSerializable
{
    /**
     * The absolute file path.
     *
     * @var string
     */
    private $file;

    /**
     * The line number.
     *
     * @var integer
     */
    private $line;

    /**
     * The function name.
     *
     * @var string
     */
    private $function;

    /**
     * The pre context lines.
     *
     * @var array
     */
    private $preContext = [];

    /**
     * The source code that originated from this frame.
     *
     * @var string|null
     */
    private $context;

    /**
     * The post context lines.
     *
     * @var array
     */
    private $postContext;

    /**
     * Constructor
     *
     * @param  string      $file        The full file path.
     * @param  integer     $line        The line number.
     * @param  string      $function    The function name.
     * @param  string|null $context     Origin line of source code.
     * @param  array       $preContext  Pre context lines.
     * @param  array       $postContext Post context lines.
     * @return void
     */
    public function __construct(
        string $file,
        int $line,
        string $function,
        ?string $context = null,
        array $preContext = [],
        array $postContext = []
    ) {
        $this->file = $file;
        $this->line = $line;
        $this->function = $function;
        $this->context = $context;
        $this->preContext = $preContext;
        $this->postContext = $postContext;
    }

    /**
     * Get the absolute file path.
     *
     * @return string
     */
    public function file(): string
    {
        return $this->file;
    }

    /**
     * Get the line number.
     *
     * @return integer
     */
    public function line(): int
    {
        return $this->line;
    }

    /**
     * Get the function name.
     *
     * @return string
     */
    public function function(): string
    {
        return $this->function;
    }

    /**
     * Get the context source code line.
     *
     * @return string|null
     */
    public function context(): ?string
    {
        return $this->context;
    }

    /**
     * Get the pre context.
     *
     * @return array
     */
    public function preContext(): array
    {
        return $this->preContext;
    }

    /**
     * Get the post context.
     *
     * @return array
     */
    public function postContext(): array
    {
        return $this->postContext;
    }

    /**
     * Serialize the object.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'file' => $this->file(),
            'line' => $this->line(),
            'function' => $this->function(),
            'context' => $this->context(),
            'pre_context' => $this->preContext(),
            'post_context' => $this->postContext(),
        ];
    }

    /**
     * Create a frame object from a trace array.
     *
     * @param  array $trace The trace array.
     * @return \Pinge\SDK\Stacktrace\Frame
     */
    public static function createFromTrace(array $trace): self
    {
        $context = null;
        $preContext = [];
        $postContext = [];

        // Determine if the source file is readable.
        if (is_readable($trace['file'])) {
            $file = new SplFileObject($trace['file']);

            // Get the pre context.
            $start = max(0, $trace['line'] - 6);
            for ($i = $start; $i < ($trace['line'] - 1); $i++) {
                $file->seek($i);
                array_push($preContext, new Context($i + 1, $file->current()));
            }

            // Get the context.
            if (!$file->eof()) {
                $file->seek($trace['line'] - 1);
                $context = $file->current();
            }

            // Get the post context.
            $start = $trace['line'];
            $stop = $trace['line'] + 5;
            for ($i = $start; $i < $stop; $i++) {
                $file->seek($i);

                // Break out if there is no more lines.
                if ($file->eof()) {
                    break;
                }

                array_push($postContext, new Context($i + 1, $file->current()));
            }
        }

        return new self(
            $trace['file'],
            $trace['line'],
            $trace['function'],
            $context,
            $preContext,
            $postContext
        );
    }
}
