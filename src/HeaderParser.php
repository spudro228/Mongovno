<?php

declare(strict_types=1);

namespace Mongovno;


class HeaderParser
{
    public const MSG_HEADER_SIZE = 16;

    /**
     * HeaderParser constructor.
     */
    public function __construct()
    {
    }

    public function parse(string $data): MessageHeader
    {
        $values = array_values(unpack('VmessageLength/VrequestId/VresponseTo/VopCode', substr($data, 0, MessageHeader::MSG_HEADER_SIZE)));

        return MessageHeader::create(...$values);
    }
}