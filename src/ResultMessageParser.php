<?php

declare(strict_types=1);

namespace AfmImporter;


class ResultMessageParser
{
    public function parse(string $data): MessageResult
    {
        $values = array_values(unpack('VresponseFlags/qcursorId/VstartingFrom/VnumberReturned', $data));

        $documentsData = substr($data, MessageResult::MSG_WITHOUT_DATA_SIZE);
        $documentsData = new BsonIterator($documentsData);
//        $k = iterator_to_array($documentsData);
        $values[] = $documentsData;

        return MessageResult::create(...$values);
    }
}