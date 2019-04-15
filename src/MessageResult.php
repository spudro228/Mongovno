<?php

declare(strict_types=1);

namespace AfmImporter;


final class MessageResult
{
    /**
     * @var int
     */
    private $responseFlags;
    /**
     * @var int
     */
    private $cursorId;
    /**
     * @var int
     */
    private $startingFrom;
    /**
     * @var int
     */
    private $numberReturned;
    /**
     * @var \Iterator
     */
    private $documents;


    public const MSG_WITHOUT_DATA_SIZE = 20;

    public static function create(int $responseFlags, int $cursorId, int $startingFrom, int $numberReturned, \Iterator $documents): self
    {
        $inst = new self();
        $inst->responseFlags = $responseFlags;
        $inst->cursorId = $cursorId;
        $inst->startingFrom = $startingFrom;
        $inst->numberReturned = $numberReturned;
        $inst->documents = $documents;

        return $inst;
    }

    /**
     * @return int
     */
    public function responseFlags(): int
    {
        return $this->responseFlags;
    }

    /**
     * @return int
     */
    public function cursorId(): int
    {
        return $this->cursorId;
    }

    /**
     * @return int
     */
    public function startingFrom(): int
    {
        return $this->startingFrom;
    }

    /**
     * @return int
     */
    public function numberReturned(): int
    {
        return $this->numberReturned;
    }

    /**
     * @return \Iterator
     */
    public function documents(): \Iterator
    {
        return $this->documents;
    }
}