<?php

namespace Diegonz\PHPWakeOnLan\Tests;

use Diegonz\PHPWakeOnLan\CidrNetwork;
use PHPUnit\Framework\TestCase;

class CidrNetworkTest extends TestCase
{
    public function testItInstantiates(): void
    {
        $cidrNetwork = new CidrNetwork();
        $this->assertInstanceOf(CidrNetwork::class, $cidrNetwork);

        $cidrNetwork = CidrNetwork::make();
        $this->assertInstanceOf(CidrNetwork::class, $cidrNetwork);
    }

    public function testItCalculatesTheNetworkAddressesCorrectly(): void
    {
        // Large Private Networks
        $cidrNetwork = CidrNetwork::make('10.0.0.1', '255.0.0.0');
        $this->assertSame('255.0.0.0', $cidrNetwork->getSubnetMask());
        $this->assertSame('10.0.0.0', $cidrNetwork->getNetworkAddress());
        $this->assertSame('10.255.255.255', $cidrNetwork->getBroadcastAddress());

        // Medium Private Networks
        $cidrNetwork = CidrNetwork::make('172.16.0.1', '255.240.0.0');
        $this->assertSame('255.240.0.0', $cidrNetwork->getSubnetMask());
        $this->assertSame('172.16.0.0', $cidrNetwork->getNetworkAddress());
        $this->assertSame('172.31.255.255', $cidrNetwork->getBroadcastAddress());

        // Small Private Networks
        $cidrNetwork = CidrNetwork::make('192.168.0.1', '255.255.0.0');
        $this->assertSame('255.255.0.0', $cidrNetwork->getSubnetMask());
        $this->assertSame('192.168.0.0', $cidrNetwork->getNetworkAddress());
        $this->assertSame('192.168.255.255', $cidrNetwork->getBroadcastAddress());

        // Custom Private Networks
        $cidrNetwork = CidrNetwork::make('192.168.50.1', '255.255.255.252'); // 30 bit subnet mask
        $this->assertSame('255.255.255.252', $cidrNetwork->getSubnetMask());
        $this->assertSame('192.168.50.0', $cidrNetwork->getNetworkAddress());
        $this->assertSame('192.168.50.3', $cidrNetwork->getBroadcastAddress());

        // Network addresses and broadcast addresses are also calculated correctly
        $cidrNetwork = CidrNetwork::make('192.168.0.0', '255.255.0.0');
        $this->assertSame('255.255.0.0', $cidrNetwork->getSubnetMask());
        $this->assertSame('192.168.0.0', $cidrNetwork->getNetworkAddress());
        $this->assertSame('192.168.255.255', $cidrNetwork->getBroadcastAddress());
        $cidrNetwork = CidrNetwork::make('192.168.255.255', '255.255.0.0');
        $this->assertSame('255.255.0.0', $cidrNetwork->getSubnetMask());
        $this->assertSame('192.168.0.0', $cidrNetwork->getNetworkAddress());
        $this->assertSame('192.168.255.255', $cidrNetwork->getBroadcastAddress());
    }

    public function testItInterpretsCidrSubnetMaskBits(): void
    {
        $cidrNetwork = CidrNetwork::make('192.168.0.1', 24);
        $this->assertSame('255.255.255.0', $cidrNetwork->getSubnetMask());
        $this->assertSame('192.168.0.0', $cidrNetwork->getNetworkAddress());
        $this->assertSame('192.168.0.255', $cidrNetwork->getBroadcastAddress());

        $cidrNetwork = CidrNetwork::make('192.168.0.1', 32);
        $this->assertSame('255.255.255.255', $cidrNetwork->getSubnetMask());
        $this->assertSame('192.168.0.1', $cidrNetwork->getNetworkAddress());
        $this->assertSame('192.168.0.1', $cidrNetwork->getBroadcastAddress());

        // Caps the bits at 32
        $cidrNetwork = CidrNetwork::make('192.168.0.1', 64);
        $this->assertSame('255.255.255.255', $cidrNetwork->getSubnetMask());
        $this->assertSame('192.168.0.1', $cidrNetwork->getNetworkAddress());
        $this->assertSame('192.168.0.1', $cidrNetwork->getBroadcastAddress());
    }

    public function testItValidatesTheIpAddress(): void
    {
        $this->expectExceptionMessage('Invalid IPv4 IP Address: aaa');
        CidrNetwork::make('aaa');
    }

    public function testItValidatesTheSubnetMask(): void
    {
        $this->expectExceptionMessage('Invalid IPv4 Subnet Mask: aaa');
        CidrNetwork::make('192.168.0.1', 'aaa');
    }
}