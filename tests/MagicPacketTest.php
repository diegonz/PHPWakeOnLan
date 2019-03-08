<?php

namespace Diegonz\PHPWakeOnLan\Tests;

use Diegonz\PHPWakeOnLan\MagicPacket;
use PHPUnit\Framework\TestCase;

/**
 * Class MagicPacketTest
 *
 * @covers  \Diegonz\PHPWakeOnLan\MagicPacket
 *
 * @package Diegonz\PHPWakeOnLan\Tests
 */
class MagicPacketTest extends TestCase
{

    /**
     * @covers \Diegonz\PHPWakeOnLan\MagicPacket::isMacAddressValid()
     */
    public function testIsMacAddressValid()
    {
        $this->assertTrue(MagicPacket::isMacAddressValid('00:1B:21:1C:7F:23'));
        $this->assertFalse(MagicPacket::isMacAddressValid(' 00:1P:21:1X:7F:23'));
        $this->assertFalse(MagicPacket::isMacAddressValid(' 00:00:1P:21:1X:7F:23'));
    }

    /**
     * @covers \Diegonz\PHPWakeOnLan\MagicPacket::trimMacAddress()
     */
    public function testTrimMacAddress()
    {
        $this->assertEquals('001B211C7F23', MagicPacket::trimMacAddress(' 00:1B:21:1C:7F:23'));
    }

    /**
     * @covers \Diegonz\PHPWakeOnLan\MagicPacket::packMacAddress()
     */
    public function testPackMacAddress()
    {
        $mac1   = '00:1B:21:1C:8F:23  ';
        $mac2   = '00:1C:21:1C:8F:27  ';
        $sample = pack('H12', '001B211C8F23');

        $this->assertEquals($sample, MagicPacket::packMacAddress($mac1));
        $this->assertNotEquals($sample, MagicPacket::packMacAddress($mac2));
    }

    /**
     * @covers \Diegonz\PHPWakeOnLan\MagicPacket::buildMagicPacketString()
     * @throws \Exception
     */
    public function testBuildMagicPacketString()
    {
        $mac1   = '00:1B:21:1C:8F:23';
        $mac2   = '00:1C:21:1C:8F:27';
        $sample = \str_repeat(\chr(0xff), 6)
                  .\str_repeat(pack('H12', '001B211C8F23'), 16);

        $magicPacket1 = new MagicPacket($mac1);
        $magicPacket2 = new MagicPacket($mac2);

        $this->assertEquals($sample, $magicPacket1);
        $this->assertNotEquals($sample, $magicPacket2);
    }
}
