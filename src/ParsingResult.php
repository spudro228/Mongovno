<?php

declare(strict_types=1);

namespace Mongovno;

class ParsingResult
{

    /**
     * @var MessageResult
     */
    private $resultMessage;

    /**
     * @var MessageHeader
     */
    private $messageHeader;

    public function __construct(MessageHeader $header, MessageResult $result)
    {
        $this->messageHeader = $header;
        $this->resultMessage = $result;
    }

    public function header(): MessageHeader
    {
        return $this->messageHeader;
    }

    public function result(): MessageResult
    {
        return $this->resultMessage;
    }
}