<?php

declare(strict_types=1);

namespace Mongovno;


class MessageResult
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
     * @var string
     */
    private $documents;


    public const MSG_WITHOUT_DATA_SIZE = 20;

    public static function create(int $responseFlags, int $cursorId, int $startingFrom, int $numberReturned, string $documents): self
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
        $offset = 0;

        $dataLength = strlen($this->documents);

        if ($dataLength - $offset < 5) {
            throw new \RuntimeException(sprintf('Expected at least 5 bytes; %d remaining', $dataLength - $offset));
        }

        while ($dataLength !== $offset) {
            [, $documentLength] = unpack('V', substr($this->documents, $offset, 4));

            yield \MongoDB\BSON\toPHP(substr($this->documents, $offset, $documentLength), []);
            $offset += $documentLength;
        }
    }
}