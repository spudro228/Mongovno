<?php

declare(strict_types=1);

namespace Mongovno\Opcode;


use Mongovno\Binary;

class Query implements Binary
{
    public const OP_QUERY = 2004;

    /**
     * @var \Mongovno\Query
     */
    private $query;
    /**
     * @var int
     */
    private $flags;
    /**
     * @var string
     */
    private $fullCollectionName;
    /**
     * @var int
     */
    private $numberToSkip;
    /**
     * @var int
     */
    private $numberToReturn;

    public function __construct(\Mongovno\Query $query, string $fullCollectionName, int $flags = 0, int $numberToSkip = 0, int $numberToReturn = 100)
    {
        $this->query = $query;
        $this->flags = $flags;
        $this->fullCollectionName = $fullCollectionName;
        $this->numberToSkip = $numberToSkip;
        $this->numberToReturn = $numberToReturn;
    }

    public function toBinary(): string
    {
        return pack(
            'Va*xVVa*',
            $this->flags,
            $this->fullCollectionName,
            $this->numberToSkip,
            $this->numberToReturn,
            \MongoDB\BSON\fromPHP($this->query)
        );
    }
}