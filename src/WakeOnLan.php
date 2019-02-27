<?php

namespace Diegonz\PHPWakeOnLan;

use Diegonz\PHPWakeOnLan\Socket\UdpBroadcastSocket;

/**
 * Class WakeOnLan
 *
 * @package Diegonz\PHPWakeOnLan
 */
class WakeOnLan
{

    /**
     * @var string $broadcastAddress
     */
    protected $broadcastAddress = '255.255.255.255';

    /**
     * Target port to send magic packet, 7 or 9
     *
     * @var int $port
     */
    protected $port = 7;

    /**
     * MagicPacket array
     *
     * @var array $magicPackets
     */
    protected $magicPackets = [];

    /**
     * Broadcast enabled UDP Socket
     *
     * @var UdpBroadcastSocket $udpBroadcastSocket
     */
    protected $udpBroadcastSocket;

    /**
     * WakeOnLan constructor.
     *
     * @param array    $macAddresses     Array of mac addresses (or a single string) in XX:XX:XX:XX:XX:XX hexadecimal
     *                                   format. Only 0-9 and a-f are allowed
     * @param string   $broadcastAddress String containing target broadcast address in XXX.XXX.XXX.255 format
     * @param int|null $port             Target port to send magic packet, 7 or 9
     *
     * @throws \Exception
     */
    public function __construct(
        array $macAddresses,
        string $broadcastAddress = null,
        int $port = null
    ) {
        foreach ($macAddresses as $macAddress) {
            $this->magicPackets[] = new MagicPacket($macAddress);
        }
        if ($broadcastAddress) {
            if (! self::isBroadcastAddressValid($broadcastAddress)) {
                throw new \RuntimeException("Error: Invalid Broadcast address [$broadcastAddress]", 3);
            }
            $this->broadcastAddress = $broadcastAddress;
        }
        if ($port) {
            $this->port = $port;
        }
        $this->udpBroadcastSocket = new UdpBroadcastSocket();
    }

    /**
     * Perform a simple IPV4 broadcast address validation
     *
     * @param string $broadcastAddress String containing target broadcast address in XXX.XXX.XXX.255 format
     *
     * @return boolean True if given broadcast address is valid
     */
    public static function isBroadcastAddressValid(string $broadcastAddress): bool
    {
        return 0 < preg_match("/^[1,2]\d{1,2}\.[1,2]\d{1,2}\.[1,2]\d{0,2}\.255$/", trim($broadcastAddress));
    }

    /**
     * Wake up target devices using given mac address(es) to build magic packets
     * and send them to broadcast address
     *
     * @return array Detailed results array with result, bytes sent and a message for each given magic packet
     *
     */
    public function wake(): array
    {
        $result = [];
        foreach ($this->magicPackets as $magicPacket) {
            $macAddress = $magicPacket->getMacAddress();
            $bytes      = $this->udpBroadcastSocket->send($magicPacket, $macAddress, $this->port);
            $result     = ! empty($bytes) && $bytes > 0;
            $message    = $result ? 'Magic packet sent' : '0 bytes sent';
            $message    .= ' to '.$macAddress.' through '.$this->broadcastAddress;

            $result[$macAddress] = [
                'result'     => $result ? 'OK' : 'KO',
                'message'    => $message,
                'bytes_sent' => $bytes,
            ];
        }
        $this->udpBroadcastSocket->close();

        return count($result) > 1 ? $result : $result[$this->magicPackets[0]->getMacAddress()];
    }
}
