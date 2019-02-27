<?php

namespace Diegonz\PHPWakeOnLan\Socket;

class Socket
{

    /**
     * @var resource $socket
     */
    protected $socket;

    /**
     * Socket constructor.
     *
     * @param int $type Socket type SOL_TCP (6) or SOL_UDP (17)
     */
    public function __construct(int $type)
    {
        if ( ! \in_array($type, [SOL_TCP, SOL_UDP], true)) {
            throw new \RuntimeException('Error: Wrong socket type', 4);
        }
        if ( ! $this->socket = \socket_create(AF_INET, SOCK_DGRAM, $type)) {
            throw new \RuntimeException('Error: Could not open UDP socket', 4);
        }
    }

    /**
     * Close socket
     */
    public function close()
    {
        \socket_close($this->socket);
    }

    /**
     * Send string through socket to target address
     *
     * @param string $string
     * @param string $address
     * @param int    $port
     *
     * @return int
     */
    public function send(string $string, string $address, int $port): int
    {
        $result = \socket_sendto($this->socket, $string, \strlen($string), 0, $address, $port);
        if ($result === false) {
            throw new \RuntimeException('Error: Could not send data through socket', 7);
        }

        return $result;
    }

    /**
     * @return resource
     */
    public function getSocket()
    {
        return $this->socket;
    }
}
