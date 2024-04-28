<?php

namespace Diegonz\PHPWakeOnLan;

use RuntimeException;

/**
 * Class MagicPacket.
 */
class MagicPacket
{
    /**
     * @var string
     */
    protected $magicPacket;

    /**
     * @var string
     */
    protected $macAddress;

    /**
     * MagicPacket constructor.
     *
     * @param  string  $macAddress
     *
     * @throws \Exception
     */
    public function __construct(string $macAddress)
    {
        $this->setMacAddress($macAddress);
    }

    /**
     * Returns given mac address without spaces and colons.
     *
     * @param  string  $macAddressHex  Array of mac addresses (or a single string)
     *                                 in XX:XX:XX:XX:XX:XX hexadecimal format.
     *                                 Only 0-9 and a-f are allowed.
     * @return string Given mac address trimmed without spaces and colons
     */
    public static function trimMacAddress(string $macAddressHex): string
    {
        return trim(str_replace(':', '', $macAddressHex));
    }

    /**
     * Checks mac address validity.
     *
     * @param  string  $macAddressHex  Mac addresses string in XX:XX:XX:XX:XX:XX
     *                                 hexadecimal format. Only 0-9 and a-f are
     *                                 allowed.
     * @return bool True if given mac address is valid
     */
    public static function isMacAddressValid(string $macAddressHex): bool
    {
        return ctype_xdigit(self::trimMacAddress($macAddressHex));
    }

    /**
     * Returns given mac address string packed to H12 binary format.
     *
     * @param  string  $macAddressHex  Array of mac addresses (or a single string) in XX:XX:XX:XX:XX:XX hexadecimal
     *                                 format. Only 0-9 and a-f are allowed
     * @return string Trimmed mac address string, packed to H12 binary format
     */
    public static function packMacAddress(string $macAddressHex): string
    {
        return pack('H12', self::trimMacAddress($macAddressHex));
    }

    /**
     * Returns magic packet string built based on given mac address.
     *
     * @param  string  $macAddressHex  Array of mac addresses (or a single string) in XX:XX:XX:XX:XX:XX hexadecimal
     *                                 format. Only 0-9 and a-f are allowed
     * @return string Built magic packet based on mac address
     */
    protected function buildMagicPacketString(string $macAddressHex): string
    {
        // $prefix = pack('H12', str_repeat('FF', 6));
        $prefix = str_repeat(chr(0xFF), 6);
        $binMacAddress = self::packMacAddress($macAddressHex);
        $suffix = str_repeat($binMacAddress, 16);

        return $prefix.$suffix;
    }

    /**
     * @return string
     */
    public function getMacAddress(): string
    {
        return $this->macAddress;
    }

    /**
     * @param  string  $macAddress
     *
     * @throws \Exception
     */
    public function setMacAddress(string $macAddress): void
    {
        $this->macAddress = $macAddress;

        if (! self::isMacAddressValid($this->macAddress)) {
            throw new RuntimeException("Error: Mac address invalid [$macAddress].", 2);
        }
        $this->magicPacket = $this->buildMagicPacketString($this->macAddress);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->magicPacket;
    }
}
