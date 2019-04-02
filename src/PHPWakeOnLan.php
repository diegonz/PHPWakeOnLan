<?php

namespace Diegonz\PHPWakeOnLan;

use RuntimeException;
use Diegonz\PHPWakeOnLan\Socket\UdpBroadcastSocket;

/**
 * Class PHPWakeOnLan.
 */
class PHPWakeOnLan
{
    /**
     * @var string
     */
    protected $broadcastAddress = '255.255.255.255';

    /**
     * Target port to send magic packet, 7 or 9.
     *
     * @var int
     */
    protected $port = 7;

    /**
     * MagicPacket array.
     *
     * @var array
     */
    protected $magicPackets = [];

    /**
     * Broadcast enabled UDP Socket.
     *
     * @var UdpBroadcastSocket
     */
    protected $udpBroadcastSocket;

    /**
     * PHPWakeOnLan constructor.
     *
     * @param string   $broadcastAddress String containing target broadcast address in XXX.XXX.XXX.255 format
     * @param int|null $port             Target port to send magic packet, 7 or 9
     */
    public function __construct(
        string $broadcastAddress = null,
        int $port = null
    ) {
        $this->broadcastAddress = $broadcastAddress ?? $this->broadcastAddress;
        if (! self::isBroadcastAddressValid($this->broadcastAddress)) {
            throw new RuntimeException('Error: Invalid Broadcast address ['.$broadcastAddress.'].', 3);
        }

        $this->port = $port ?? $this->port;
        if (! in_array($this->port, [7, 9], true)) {
            throw new RuntimeException('Error: Invalid port ['.$port.']. Must be 7 or 9.', 4);
        }

        $this->udpBroadcastSocket = new UdpBroadcastSocket();
    }

    /**
     * Perform a simple IPV4 broadcast address validation.
     *
     * @param string $broadcastAddress String containing target broadcast address in XXX.XXX.XXX.255 format
     *
     * @return bool True if given broadcast address is valid
     */
    public static function isBroadcastAddressValid(string $broadcastAddress): bool
    {
        $broadcastAddress = trim($broadcastAddress);

        return ip2long($broadcastAddress)
            && 0 < preg_match("/^[1,2]\d{1,2}\.[1,2]\d{1,2}\.[1,2]\d{0,2}\.255$/", $broadcastAddress);
    }

    /**
     * Wake up target devices using given mac address(es) to build magic packets
     * and send them to broadcast address.
     *
     * @param array $macAddresses        Array of mac addresses (or a single string) in XX:XX:XX:XX:XX:XX hexadecimal
     *                                   format. Only 0-9 and a-f are allowed
     *
     * @return array Detailed results array with result, bytes sent and a message for each given magic packet
     *
     * @throws \Exception
     */
    public function wake(array $macAddresses): array
    {
        foreach ($macAddresses as $macAddress) {
            $this->magicPackets[] = new MagicPacket($macAddress);
        }
        $result = [];
        foreach ($this->magicPackets as $magicPacket) {
            $macAddress = $magicPacket->getMacAddress();
            $bytes = $this->udpBroadcastSocket->send($magicPacket, $this->broadcastAddress, $this->port);
            $sendOk = ! empty($bytes) && $bytes > 0;
            $message = $sendOk ? 'Magic packet sent' : '0 bytes sent';
            $message .= ' to '.$macAddress.' through '.$this->broadcastAddress;

            $result[$macAddress] = [
                'result'     => $sendOk ? 'OK' : 'KO',
                'message'    => $message,
                'bytes_sent' => $bytes,
            ];
        }
        $this->udpBroadcastSocket->close();

        return count($result) > 1 ? $result : $result[$this->magicPackets[0]->getMacAddress()];
    }
}
