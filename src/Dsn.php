<?php
declare(strict_types = 1);

namespace Pinge\SDK;

final class Dsn
{
    /**
     * The protocol to be used to access the resource.
     *
     * @var string
     */
    private $scheme;

    /**
     * The host that holds the resource.
     *
     * @var string
     */
    private $host;

    /**
     * The port on which the resource is exposed.
     *
     * @var integer
     */
    private $port;

    /**
     * The token to authenticate the SDK.
     *
     * @var string
     */
    private $token;

    /**
     * Create an instance using a string.
     *
     * @param  string $dsn The DSN.
     * @return \Pinge\SDK\Dsn
     *
     * @throws \InvalidArgumentException Thrown on invalid DSN string.
     */
    public static function createFromString(string $dsn): self
    {
        if (($parts = parse_url($dsn)) === false) {
            throw new \InvalidArgumentException("The DSN is invalid ({$dsn}).");
        }

        foreach (['scheme', 'host', 'user'] as $component) {
            if (!array_key_exists($component, $parts) || !strlen((string) $parts[$component])) {
                throw new \InvalidArgumentException("The DSN ({$dsn}) must contain a scheme, host, path and user.");
            }
        }

        if (!in_array($parts['scheme'], ['http', 'https'], true)) {
            throw new \InvalidArgumentException("The scheme of the DSN ({$dsn}) must be either http or https.");
        }

        if (!array_key_exists('port', $parts)) {
            $parts['port'] = $parts['scheme'] === 'http'
                ? 80
                : 443;
        }

        return new self(
            $parts['scheme'],
            $parts['host'],
            $parts['port'],
            $parts['user']
        );
    }

    /**
     * Constructor
     *
     * @param  string  $scheme The protocol scheme.
     * @param  string  $host   The host.
     * @param  integer $port   The port number.
     * @param  string  $token  The project token.
     * @return void
     */
    public function __construct(string $scheme, string $host, int $port, string $token)
    {
        $this->scheme = $scheme;
        $this->host = $host;
        $this->port = $port;
        $this->token = $token;
    }

    /**
     * Get the scheme.
     *
     * @return string
     */
    public function scheme(): string
    {
        return $this->scheme;
    }

    /**
     * Get the host.
     *
     * @return string
     */
    public function host(): string
    {
        return $this->host;
    }

    /**
     * Get the port number.
     *
     * @return integer
     */
    public function port(): int
    {
        return $this->port;
    }

    /**
     * Get the token.
     *
     * @return string
     */
    public function token(): string
    {
        return $this->token;
    }

    /**
     * Cast this object into a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            '%s://%s@%s%s/beacon',
            $this->scheme(),
            $this->token(),
            $this->host(),
            ($this->scheme() === 'http' && $this->port() !== 80) ||
                ($this->scheme() === 'https' && $this->port() !== 443)
                ? ':' . $this->port()
                : null
        );
    }
}
