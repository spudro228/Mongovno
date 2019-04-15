<?php

declare(strict_types=1);

use AfmImporter\HeaderParser;
use AfmImporter\MessageHeader;
use PHPUnit\Framework\TestCase;

class HeaderParserTest extends TestCase
{

    public function test_valid_data(): void
    {
        $data = file_get_contents(__DIR__ . '/mongo_binary_data/data.txt');
        $parser = new HeaderParser();
        /** @var MessageHeader $responseHeader */
        $messageHeader = $parser->parse($data);
        self::assertEquals(1301, $messageHeader->messageLength());
        self::assertEquals(16, $messageHeader->requestId());
        self::assertEquals(123123123, $messageHeader->responseTo());
        self::assertEquals(1, $messageHeader->opCode());

    }
}