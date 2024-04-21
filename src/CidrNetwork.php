<?php

namespace Diegonz\PHPWakeOnLan;

class CidrNetwork
{
    /** @var string $networkAddress */
    protected $networkAddress;

    /** @var string $subnetMask */
    protected $subnetMask;

    /** @var string $broadcastAddress */
    protected $broadcastAddress;

    public function __construct(string $ipAddress = '192.168.0.1', string $subnetMask = '255.255.255.0')
    {
        $this->subnetMask = $subnetMask;
        $this->networkAddress = $this->calculateNetworkAddress($ipAddress, $subnetMask);
        $this->broadcastAddress = $this->calculateBroadcastAddress($ipAddress, $subnetMask);
    }

    protected function calculateBroadcastAddress(string $networkAddress, string $subnetMask): string
    {
        return long2ip(ip2long($networkAddress) | ~ip2long($subnetMask));
    }

    protected function calculateNetworkAddress(string $ipAddress, string $subnetMask): string
    {
        return long2ip(ip2long($ipAddress) & ip2long($subnetMask));
    }

    public static function make(string $ipAddress = '192.168.0.1', string $subnetMask = '255.255.255.0'): self
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