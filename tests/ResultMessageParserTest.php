<?php

declare(strict_types=1);

use AfmImporter\MessageResult;
use AfmImporter\ResultMessageParser;
use PHPUnit\Framework\TestCase;

class ResultMessageParserTest extends TestCase
{

    public function test_parse_valid_data(): void
    {
        $data = file_get_contents(__DIR__ . '/mongo_binary_data/data_without_header.txt');
        if ($data === false) {
            $this->fail('Cannot open data_without_header.txt');
        }

        $parser = new ResultMessageParser();

        /** @var MessageResult $resultMessage */
        $resultMessage = $parser->parse($data);
        self::assertEquals(8, $resultMessage->responseFlags());
        self::assertEquals(0, $resultMessage->cursorId());
        self::assertEquals(0, $resultMessage->startingFrom());
        self::assertEquals(1, $resultMessage->numberReturned());

        $documents = [
            'version' => '4.0.4',
            'gitVersion' => 'f288a3bdf201007f3693c58e140056adf8b04839',
            'modules' =>
                [
                ],
            'allocator' => 'tcmalloc',
            'javascriptEngine' => 'mozjs',
            'sysInfo' => 'deprecated',
            'versionArray' =>
                [
                    0 => 4,
                    1 => 0,
                    2 => 4,
                    3 => 0,
                ],
            'openssl' =>
                [
                    'running' => 'OpenSSL 1.0.2g  1 Mar 2016',
                    'compiled' => 'OpenSSL 1.0.2g  1 Mar 2016',
                ],
            'buildEnvironment' =>
                [
                    'distmod' => 'ubuntu1604',
                    'distarch' => 'x86_64',
                    'cc' => '/opt/mongodbtoolchain/v2/bin/gcc: gcc (GCC) 5.4.0',
                    'ccflags' => '-fno-omit-frame-pointer -fno-strict-aliasing -ggdb -pthread -Wall -Wsign-compare -Wno-unknown-pragmas -Winvalid-pch -Werror -O2 -Wno-unused-local-typedefs -Wno-unused-function -Wno-deprecated-declarations -Wno-unused-but-set-variable -Wno-missing-braces -fstack-protector-strong -fno-builtin-memcmp',
                    'cxx' => '/opt/mongodbtoolchain/v2/bin/g++: g++ (GCC) 5.4.0',
                    'cxxflags' => '-Woverloaded-virtual -Wno-maybe-uninitialized -std=c++14',
                    'linkflags' => '-pthread -Wl,-z,now -rdynamic -Wl,--fatal-warnings -fstack-protector-strong -fuse-ld=gold -Wl,--build-id -Wl,--hash-style=gnu -Wl,-z,noexecstack -Wl,--warn-execstack -Wl,-z,relro',
                    'target_arch' => 'x86_64',
                    'target_os' => 'linux',
                ],
            'bits' => 64,
            'debug' => false,
            'maxBsonObjectSize' => 16777216,
            'storageEngines' =>
                [
                    0 => 'devnull',
                    1 => 'ephemeralForTest',
                    2 => 'mmapv1',
                    3 => 'wiredTiger',
                ],
            'ok' => 1.0,
        ];

        self::assertEquals($documents, json_decode(json_encode(iterator_to_array($resultMessage->documents())[0]), true));

    }
}