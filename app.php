<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use Amp\ByteStream\ResourceOutputStream;
use Amp\Loop;
use Amp\Socket\Socket;
use function Amp\Socket\connect;

const MSG_HEADER_SIZE = 16;

const OP_REPLY = 1;
const OP_MSG = 1000;
const OP_UPDATE = 2001;
const OP_INSERT = 2002;
const OP_QUERY = 2004;
const OP_GET_MORE = 2005;
const OP_DELETE = 2006;
const OP_KILL_CURSORS = 2007;
const OP_COMMAND = 2010;
const OP_COMMANDREPLY = 2011;


const QF_TAILABLE_CURSOR = 2;
const QF_SLAVE_OK = 4;
const QF_OPLOG_REPLAY = 8;
const QF_NO_CURSOR_TIMEOUT = 16;
const QF_AWAIT_DATA = 32;
const QF_EXHAUST = 64;
const QF_PARTIAL = 128;

const RF_CURSOR_NOT_FOUND = 1;
const RF_QUERY_FAILURE = 2;
const RF_SHARD_CONFIG_STALE = 4;
const RF_AWAIT_CAPABLE = 8;

Loop::run(static function () {
    $stdout = new ResourceOutputStream(STDOUT);
    $uri = '0.0.0.0:27017';
    /** @var Socket $socket */
    $socket = yield connect('tcp://' . $uri);


//    struct MsgHeader {
//        int32   messageLength; // total message size, including this
//    int32   requestID;     // identifier for this message
//    int32   responseTo;    // requestID from the original request
//                           //   (used in responses from db)
//    int32   opCode;        // request type - see table below for details
//}

//    struct OP_QUERY {
//        MsgHeader header;                 // standard message header
//    int32     flags;                  // bit vector of query options.  See below for details.
//    cstring   fullCollectionName ;    // "dbname.collectionname"
//    int32     numberToSkip;           // number of documents to skip
//    int32     numberToReturn;         // number of documents to return
//                                      //  in the first OP_REPLY batch
//    document  query;                  // query object.  See below for details.
//  [ document  returnFieldsSelector; ] // Optional. Selector indicating the fields
//                                      //  to return.  See below for details.
//}


    //db - name common
    $data = pack(
        'Va*xVVa*',
        0,
        'common.domain',
//        'common.domain_cat',
        0,
        5,
        MongoDB\BSON\fromPHP(
            [
                'query' => [
                ],
                ['limit' => 5]

            ]
        )
    );

    $header = pack('V4', MSG_HEADER_SIZE + strlen($data), '123123123', 0, OP_QUERY);

    $message = $header . $data;

//    $stdout->write($message);
    yield $socket->write($message);
//
//    while (null !== $chunk = yield $socket->read()) {
//        yield $stdout->write($chunk);
//    }
    $parser = new \AfmImporter\ResponseParser();
    while (($chunk = yield $socket->read()) !== null) {
        $data = $chunk;
        $data = $parser->parse($data);
        yield $stdout->write("$chunk\n");
//        echo $chunk.PHP_EOL;
    }

//    $data = yield \Amp\ByteStream\buffer($socket);

    yield $stdout->write($data);

});

function parse()
{

}