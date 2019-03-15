<?php

namespace Diegonz\PHPWakeOnLan\Tests\Socket;

use PHPUnit\Framework\TestCase;
use Diegonz\PHPWakeOnLan\Socket\Socket;

/**
 * Class SocketTest.
 *
 * @covers \Diegonz\PHPWakeOnLan\Socket\Socket
 */
class SocketTest extends TestCase
{
    /**
     * @covers \Diegonz\PHPWakeOnLan\Socket\Socket::send()
     */
    public function testSend()
    {
        $socket = new Socket(SOL_UDP);
        \socket_set_option($socket->getSocket(), SOL_SOCKET, SO_BROADCAST, true);
        $result = $socket->send('', '255.255.255.255', 7);
        $this->assertEquals(0, $result, 'Bytes sent must be 0');
    }
}
