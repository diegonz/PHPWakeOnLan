<?php

namespace Diegonz\PHPWakeOnLan\Socket;

class UdpBroadcastSocket extends Socket
{

    /**
     * UDPBroadcastSocket constructor.
     */
    public function __construct()
    {
        parent::__construct(SOL_UDP);
        $optionResult = \socket_set_option($this->socket, SOL_SOCKET, SO_BROADCAST, true);
        if ( ! $optionResult) {
            throw new \RuntimeException('Error: Could not set broadcast UDP socket', 4);
        }
    }
}
