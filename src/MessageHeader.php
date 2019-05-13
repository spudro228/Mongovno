<?php

declare(strict_types=1);

namespace Mongovno;


class MessageHeader implements Binary
{
    public const MSG_HEADER_SIZE = 16;

    /**
     * @var int
     */
    private $messageLength;
    /**
     * @var int
     */
    private $requestId;
    /**
     * @var int
     */
    private $responseTo;
    /**
     * @var int
     */
    private $opCode;

    public function __construct(int $messageLength, int $requestId, int $responseTo, int $opCode)
    {
        $this->messageLength = $messageLength;
        $this->requestId = $requestId;
        $this->responseTo = $responseTo;
        $this->opCode = $opCode;
    }

    /**
     * @return int
     */
    public function messageLength(): int
    {
        return $this->messageLength;
    }

    /**
     * @return int
     */
    public function requestId(): int
    {
        return $this->requestId;
    }

    /**
     * @return int
     */
    public function responseTo(): int
    {
        return $this->responseTo;
    }

    /**
     * @return int
     */
    public function opCode(): int
    {
        return $this->opCode;
    }

    public function toBinary(): string
    {
        return pack('V4', self::MSG_HEADER_SIZE + $this->messageLength, $this->requestId, $this->responseTo, $this->opCode);
    }
}