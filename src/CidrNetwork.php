<?php

namespace Diegonz\PHPWakeOnLan;

use RuntimeException;

class CidrNetwork
{
    /** @var string */
    protected $networkAddress;

    /** @var string */
    protected $subnetMask;

    /** @var string */
    protected $broadcastAddress;

    /**
     * @param  string  $ipAddress  IP Address in the network or Network address of the network.
     * @param  string|int  $subnetMask  Subnet Mask as a string or number of bits from CIDR notation
     */
    public function __construct(string $ipAddress = '192.168.0.1', $subnetMask = '255.255.255.0')
    {
        if (is_int($subnetMask)) {
            $subnetMask = $this->getSubnetMaskFromCidrBits($subnetMask);
        }

        if (! $this->validIpv4Address($ipAddress)) {
            throw new RuntimeException("Invalid IPv4 IP Address: {$ipAddress}");
        }

        if (! $this->validIpv4Address($subnetMask)) {
            throw new RuntimeException("Invalid IPv4 Subnet Mask: {$subnetMask}");
        }

        $this->subnetMask = $subnetMask;
        $this->networkAddress = $this->calculateNetworkAddress($ipAddress, $subnetMask);
        $this->broadcastAddress = $this->calculateBroadcastAddress($ipAddress, $subnetMask);
    }

    protected function validIpv4Address(string $ipAddress): bool
    {
        return (bool) filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }

    protected function calculateBroadcastAddress(string $networkAddress, string $subnetMask): string
    {
        return long2ip(ip2long($networkAddress) | ~ip2long($subnetMask));
    }

    protected function calculateNetworkAddress(string $ipAddress, string $subnetMask): string
    {
        return long2ip(ip2long($ipAddress) & ip2long($subnetMask));
    }

    protected function getSubnetMaskFromCidrBits(int $numberOfBits): string
    {
        // Create the 32 bit subnet mask
        $binary = '';
        for ($i = 0; $i < min($numberOfBits, 32); $i++) {
            $binary .= '1';
        }
        $binary = str_pad($binary, 32, '0');

        // Split it into the 4 octets of 8 bits each
        $octets = str_split($binary, 8);

        // Convert each octet into decimal and concatenate with a dot
        return array_reduce(
            $octets,
            function (string $subnetMask, string $octet) {
                if (! $subnetMask) {
                    return $subnetMask.bindec($octet);
                }

                return $subnetMask.'.'.bindec($octet);
            },
            ''
        );
    }

    /**
     * @param  string  $ipAddress  IP Address in the network or Network address of the network.
     * @param  string|int  $subnetMask  Subnet Mask as a string or number of bits from CIDR notation
     * @return CidrNetwork
     */
    public static function make(string $ipAddress = '192.168.0.1', $subnetMask = '255.255.255.0'): self
    {
        return new static($ipAddress, $subnetMask);
    }

    public function getNetworkAddress(): string
    {
        return $this->networkAddress;
    }

    public function getSubnetMask(): string
    {
        return $this->subnetMask;
    }

    public function getBroadcastAddress(): string
    {
        return $this->broadcastAddress;
    }
}
