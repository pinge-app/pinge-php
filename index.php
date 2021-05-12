<?php
declare(strict_types = 1);

use Pinge\SDK\Dsn;
use Pinge\SDK\Client;
use PHPUnit\Framework\Exception;
use Pinge\SDK\Transport\HttpTransport;

require __DIR__ . '/vendor/autoload.php';

$client = new Client(Dsn::createFromString('http://d4480fe09bde00e6be1a600e503b2bff@localhost:3000/1'), new HttpTransport);

function ohoh()
{
    throw new Exception('Something broke!', 25);
}

try {
    ohoh();
} catch (Throwable $e) {
    var_dump($client->captureException($e));
}
