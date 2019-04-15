<?php

declare(strict_types=1);

use Mongovno\MessageHeader;
use Mongovno\MessageResult;
use Mongovno\ResponseParser;
use PHPUnit\Framework\TestCase;

class MongoResponseParserTest extends TestCase
{

    public function test_parse_valid_response(): void
    {
        $data = file_get_contents(__DIR__ . '/mongo_binary_data/data.txt');
        $parser = new ResponseParser();
        $parsingResult = $parser->parse($data);
        self::assertInstanceOf(MessageHeader::class, $parsingResult->header());
        self::assertInstanceOf(MessageResult::class, $parsingResult->result());
    }
}
