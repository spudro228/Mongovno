<?php

declare(strict_types=1);

namespace AfmImporter;

class ResponseParser
{
    /**
     * @var HeaderParser
     */
    private $headerParser;
    /**
     * @var ResultMessageParser
     */
    private $messageParser;

    public function __construct()
    {
        $this->headerParser = new HeaderParser();
        $this->messageParser = new ResultMessageParser();

    }

    public function parse(string $data): ParsingResult
    {
        $header = substr($data, 0, HeaderParser::MSG_HEADER_SIZE);
        $payload = substr($data, HeaderParser::MSG_HEADER_SIZE);

        return new ParsingResult(
            $this->headerParser->parse($header),
            $this->messageParser->parse($payload)
        );
    }
}