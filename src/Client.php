<?php

declare(strict_types=1);

namespace Mongovno;


use Amp\Promise;
use Amp\Socket\ClientSocket;
use function Amp\call;

class Client
{
    const MSG_HEADER_SIZE = 16;
    const OP_QUERY = 2004;


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

    public function send(string $databaseName, string $collectionName, array $query, int $offset = 0, int $limit = 100): Promise
    {

        return call(static function (ClientSocket $clientSocket, ResponseParser $responseParser) use ($databaseName, $collectionName, $query, $offset, $limit): \Generator {
            //db - name common
            $data = pack(
                'Va*xVVa*',
                0, //flags
                $databaseName . '.' . $collectionName,
                $offset,
                $limit,
                \MongoDB\BSON\fromPHP($query)
            );

            $requestUid = uniqid('', false);
            $header = pack('V4', self::MSG_HEADER_SIZE + strlen($data), $requestUid, 0, self::OP_QUERY);

            $requestMessage = $header . $data;

            yield $clientSocket->write($requestMessage);

            /** @var string $blobDataFromMongo */
            $blobDataFromMongo = yield $clientSocket->read();

            return $responseParser->parse($blobDataFromMongo)->documents();

        }, $this->clientSocket, $this->responseParser);

    }
}