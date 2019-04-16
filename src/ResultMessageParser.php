<?php

declare(strict_types=1);

namespace Mongovno;


class ResultMessageParser
{
    public function parse(string $data): MessageResult
    {
        $values = array_values(unpack('VresponseFlags/qcursorId/VstartingFrom/VnumberReturned', $data));

        $documentsData = substr($data, MessageResult::MSG_WITHOUT_DATA_SIZE);
        $values[] = $documentsData;

        return MessageResult::create(...$values);
    }
}