<?php

declare(strict_types=1);

namespace AfmImporter;


final class MessageHeader
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

    private function __construct()
    {
    }

    public static function create(int $messageLength, int $requestId, int $responseTo, $opCode): self
    {
        $ins = new self();
        $ins->messageLength = $messageLength;
        $ins->requestId = $requestId;
        $ins->responseTo = $responseTo;
        $ins->opCode = $opCode;

        return $ins;
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
}