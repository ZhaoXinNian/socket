<?php

namespace Amp\Socket\Test;

use Amp\Socket\Socket;

class IntegrationTest extends \PHPUnit_Framework_TestCase {
    /**
     * @dataProvider provideConnectArgs
     */
    public function testConnect($uri, $options) {
        $promise = \Amp\Socket\connect($uri, $options);
        $sock = \Amp\Promise\wait($promise);
        $this->assertInstanceOf(Socket::class, $sock);
    }

    public function provideConnectArgs() {
        return [
            ['www.google.com:80', []],
            ['www.yahoo.com:80', []]
        ];
    }

    /**
     * @dataProvider provideCryptoConnectArgs
     */
    public function testCryptoConnect($uri, $options) {
        $promise = \Amp\Socket\cryptoConnect($uri, $options);
        $sock = \Amp\Promise\wait($promise);
        $this->assertInstanceOf(Socket::class, $sock);
    }

    public function provideCryptoConnectArgs() {
        return [
            ['stackoverflow.com:443', []],
            ['github.com:443', []],
            ['raw.githubusercontent.com:443', []]
        ];
    }

    public function testRenegotiation() {
        $this->markTestSkipped("Expected failure: proper renegotiation does not work yet");

        $promise = \Amp\Socket\cryptoConnect('www.google.com:443', []);
        $sock = \Amp\Promise\wait($promise);
        $promise = \Amp\Socket\cryptoEnable($sock, ["verify_peer" => false]); // force renegotiation by different option...
        $sock = \Amp\Promise\wait($promise);
        $this->assertInstanceOf(Socket::class, $sock);
    }
}
