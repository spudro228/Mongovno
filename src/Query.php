<?php

declare(strict_types=1);

namespace Mongovno;


use MongoDB\BSON\Serializable;

class Query implements Serializable
{
    /**
     * @var array
     */
    private $filter;

    /**
     * Query constructor.
     * @param array $filter
     */
    public function __construct(array $filter)
    {
        $this->filter = $filter;
    }

    public function bsonSerialize()
    {
        return [
            '$query' => $this->filter,
        ];
    }
}