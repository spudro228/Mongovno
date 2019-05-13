<?php

declare(strict_types=1);

namespace Mongovno;


use Amp\Promise;
use Amp\Socket\ClientSocket;
use function Amp\call;

class Client
{
    /**
     * @var ClientSocket
     */
    private $clientSocket;
    /**
     * @var ResponseParser
     */
    private $responseParser;

    /**
     * Client constructor.
     * @param ClientSocket $clientSocket
     * @param ResponseParser $responseParser
     */
    public function __construct(ClientSocket $clientSocket, ResponseParser $responseParser)
    {
        $this->clientSocket = $clientSocket;
        $this->responseParser = $responseParser;
    }

    public function send(string $databaseName, string $collectionName, Query $query, int $offset = 0, int $limit = 100, int $responseToId = 0): Promise
    {

        return call(static function (ClientSocket $clientSocket, ResponseParser $responseParser) use ($databaseName, $collectionName, $query, $offset, $limit, $responseToId): \Generator {
            $opQueryBinaryData =
                (new \Mongovno\Opcode\Query(
                    $query,
                $databaseName . '.' . $collectionName,
                    0,
                $offset,
                    $limit
                ))->toBinary();

            $messageHeaderBinaryData =
                (new MessageHeader(
                    strlen($opQueryBinaryData),
                    random_int(0, (2 ** 32) - 1),
                    $responseToId,
                    \Mongovno\Opcode\Query::OP_QUERY
                ))->toBinary();

            yield $clientSocket->write($messageHeaderBinaryData . $opQueryBinaryData);

            /** @var string $blobDataFromMongo */
            $blobDataFromMongo = yield $clientSocket->read();

            return $responseParser->parse($blobDataFromMongo)->documents();

        }, $this->clientSocket, $this->responseParser);

    }
}