<?php

declare(strict_types=1);

use Amp\Socket\ClientSocket;
use Mongovno\Query;
use Mongovno\ResponseParser;
use PHPUnit\Framework\TestCase;

class MongoClientTest extends TestCase
{

    public function test_build_info_request(): void
    {
        $socketClientConnection = $this->createMock(ClientSocket::class);
        $socketClientConnection
            ->method('write')
            ->willReturn(\Amp\call(function () {
            }));

        $socketClientConnection
            ->method('read')
            ->willReturn(\Amp\call(function () {
                return '';
            }));


        $parsinResult = $this->createMock(\Mongovno\ParsingResult::class);
        $parsinResult
            ->method('documents')
            ->willReturn(new \ArrayIterator([new \stdClass()]));

        $responseParser = $this->createMock(ResponseParser::class);
        $responseParser
            ->method('parse')
            ->willReturn($parsinResult);

        \Amp\Loop::run(static function () use ($socketClientConnection, $responseParser) {
            $client = new Mongovno\Client($socketClientConnection, $responseParser);
            /** @var Iterator $response */
            $response = yield $client->send(
                'common',
                'domain_cat',
                new Query(['_id' => ['$eq' => 'someidentifier']])
            );

            static::assertEquals([0 => new \stdClass(),], iterator_to_array($response));
        });
    }
}