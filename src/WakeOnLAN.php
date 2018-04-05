<?php

namespace WakeOnLAN;


/**
 * Class WakeOnLAN
 *
 * Wake up target Wake on Lan enabled device(s) by sending magic packets built based on given mac addresses and through
 * given broadcast address
 *
 * @package WakeOnLAN
 */
class WakeOnLAN
{

    /**
     * Returns given mac address without spaces and colons
     *
     * @param string $macAddressHex Array of mac addresses (or a single string) in XX:XX:XX:XX:XX:XX hexadecimal
     *                              format. Only 0-9 and a-f are allowed
     *
     * @return string Given mac address trimmed without spaces and colons
     */
    public static function trimMacAddress(string $macAddressHex)
    {
        return trim(str_replace(':', '', $macAddressHex));
    }

    /**
     * Checks mac address validity
     *
     * @param string $macAddressHex Mac addresses string in XX:XX:XX:XX:XX:XX hexadecimal format. Only 0-9 and a-f are
     *                              allowed
     *
     * @return bool True if given mac address is valid
     */
    public static function isMacAddressValid(string $macAddressHex)
    {
        return ctype_xdigit(self::trimMacAddress($macAddressHex));
    }

    /**
     * Perform a simple IPV4 broadcast address validation
     *
     * @param string $broadcastAddress String containing target broadcast address in XXX.XXX.XXX.255 format
     *
     * @return boolean True if given broadcast address is valid
     */
    public static function isBroadcastAddressValid(string $broadcastAddress)
    {
        return 0 < preg_match("/^[1,2]\d{1,2}\.[1,2]\d{1,2}\.[1,2]\d{0,2}\.255$/", trim($broadcastAddress));
    }

    /**
     * Checks validity of given mac address(es)
     *
     * @param mixed $macAddressesHex Array of mac addresses in XX:XX:XX:XX:XX:XX hexadecimal format. Only 0-9 and a-f
     *                               are allowed
     *
     * @throws \Exception If any of given mac addresses are invalid
     */
    private function checkMacAddresses(array $macAddressesHex)
    {
        foreach ($macAddressesHex as $macAddressHex) {
            if ( ! $this::isMacAddressValid($macAddressHex)) {
                throw new \Exception("Error: Mac address invalid [$macAddressHex]", 2);
            }
        }
    }

    /**
     * Checks validity of given broadcast address by performing a poor IPV4 broadcast address validation
     *
     * @param string $broadcastAddress String containing target broadcast address in XXX.XXX.XXX.255 format
     *
     * @throws \Exception If given broadcast address is invalid
     */
    private function checkBroadcastAddress(string $broadcastAddress)
    {
        if ( ! $this::isBroadcastAddressValid($broadcastAddress)) {
            throw new \Exception("Error: Broadcast address invalid [$broadcastAddress]", 3);
        }
    }

    /**
     * Returns given mac address string packed to H12 binary format
     *
     * @param string $macAddressHex Array of mac addresses (or a single string) in XX:XX:XX:XX:XX:XX hexadecimal
     *                              format. Only 0-9 and a-f are allowed
     *
     * @return string Trimmed mac address string, packed to H12 binary format
     */
    private function packMacAddress(string $macAddressHex)
    {
        return pack('H12', $this::trimMacAddress($macAddressHex));
    }

    /**
     * Returns magic packet string built based on given mac address
     *
     * @param string $macAddressHex Array of mac addresses (or a single string) in XX:XX:XX:XX:XX:XX hexadecimal
     *                              format. Only 0-9 and a-f are allowed
     *
     * @return string Built magic packet based on mac address
     */
    private function buildMagicPacket(string $macAddressHex)
    {
        return str_repeat(chr(0xff), 6).str_repeat($this->packMacAddress($macAddressHex), 16);
    }

    /**
     * Create and return an UPD socket with broadcast option already enabled and set
     *
     * @return resource Broadcast enabled UDP Socket
     * @throws \Exception If socket could not be opened
     */
    private function getUdpBroadcastSocket()
    {
        if ( ! $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP)) {
            throw new \Exception("Error: Could not open UDP socket", 4);
        }
        socket_set_option($socket, SOL_SOCKET, SO_BROADCAST, 1);

        return $socket;
    }

    /**
     * Send given magic packet to broadcast address using given socket and return total bytes sent
     *
     * @param resource $socket           Broadcast enabled UDP Socket
     * @param string   $magicPacket      Target magic packet string based on mac address
     * @param string   $broadcastAddress String containing target broadcast address in XXX.XXX.XXX.255 format (Default
     *                                   255.255.255.255)
     *
     * @param int      $port             Target port to send magic packet, 7 or 9
     *
     * @return int Total bytes sent through socket
     * @throws \Exception If magic packet could not be sent
     */
    private function sendMagicPacket($socket, string $magicPacket, string $broadcastAddress, int $port)
    {
        $result = socket_sendto($socket, $magicPacket, strlen($magicPacket), 0, $broadcastAddress, $port);
        if ($result === false) {
            throw new \Exception("Error: Could not send data through UDP socket", 7);
        }

        return $result;
    }

    /**
     * Wake on LAN target mac address through given broadcast address
     *
     * @param resource $socket           Broadcast enabled UDP Socket
     * @param string   $macAddressHex    Array of mac addresses (or a single string) in XX:XX:XX:XX:XX:XX hexadecimal
     *                                   format. Only 0-9 and a-f are allowed
     * @param string   $broadcastAddress String containing target broadcast address in XXX.XXX.XXX.255 format
     *
     * @param int      $port             Target port to send magic packet, 7 or 9
     *
     * @return array Detailed results array with result, bytes sent and a message for each given mac address
     *
     * @throws \Exception If target device could noy be woken via magic packet
     */
    private function wakeUp($socket, string $macAddressHex, string $broadcastAddress, int $port)
    {
        $magicPacket = $this->buildMagicPacket($macAddressHex);
        $bytes       = $this->sendMagicPacket($socket, $magicPacket, $broadcastAddress, $port);
        $send_result = ! empty($bytes) && $bytes > 0;
        $message     = $send_result ? "Magic packet sent" : "0 bytes sent";
        $message     .= " to $macAddressHex through $broadcastAddress";

        $result = [
            "result"     => $send_result ? "OK" : "KO",
            "message"    => $message,
            "bytes_sent" => $bytes,
        ];

        return $result;
    }

    /**
     * Wake up target devices using given mac address(es) to build magic packets and send them to broadcast address
     *
     * @param mixed  $macAddressesHex  Array of mac addresses in XX:XX:XX:XX:XX:XX hexadecimal format. Only 0-9 and a-f
     *                                 are allowed
     * @param string $broadcastAddress Target broadcast address in XXX.XXX.XXX.255 format
     *
     * @param int    $port             Target port to send magic packet, 7 or 9 (Default 7)
     *
     * @return array Results for each given mac address
     *
     * @throws \Exception
     */
    public function wake(array $macAddressesHex, string $broadcastAddress = "255.255.255.255", int $port = 7)
    {
        if ($broadcastAddress != "255.255.255.255") {
            $this->checkBroadcastAddress($broadcastAddress);
        }
        $this->checkMacAddresses($macAddressesHex);
        $socket = $this->getUdpBroadcastSocket();
        $result = [];
        foreach ($macAddressesHex as $macAddress) {
            $result[$macAddress] = $this->wakeUp($socket, $macAddress, $broadcastAddress, $port);
        }
        socket_close($socket);

        return count($result) > 1 ? $result : $result[$macAddressesHex[0]];
    }

}