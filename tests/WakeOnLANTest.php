<?php

namespace WakeOnLAN;


/**
 * Class WakeOnLANTest
 *
 * @covers  \WakeOnLAN\WakeOnLAN
 *
 * @package WakeOnLAN
 */
class WakeOnLANTest extends Test\TestCase
{

    /**
     * @covers \WakeOnLAN\WakeOnLAN::trimMacAddress()
     */
    public function testTrimMacAddress()
    {
        $this->assertEquals('001B211C7F23', $this->wol::trimMacAddress(" 00:1B:21:1C:7F:23"));
    }

    /**
     * @covers \WakeOnLAN\WakeOnLAN::trimMacAddress()
     * @covers \WakeOnLAN\WakeOnLAN::isMacAddressValid()
     */
    public function testIsMacAddressValid()
    {
        $this->assertTrue($this->wol::isMacAddressValid("00:1B:21:1C:7F:23"));
        $this->assertFalse($this->wol::isMacAddressValid(" 00:1P:21:1X:7F:23"));
    }

    /**
     * @covers \WakeOnLAN\WakeOnLAN::isBroadcastAddressValid()
     */
    public function testIsBroadcastAddressValid()
    {
        $this->assertTrue($this->wol::isBroadcastAddressValid("192.168.1.255"));
        $this->assertFalse($this->wol::isBroadcastAddressValid("192.168.1.33"));
    }

    /**
     * @covers \WakeOnLAN\WakeOnLAN::packMacAddress()
     */
    public function testPackMacAddress()
    {
        $mac1   = "00:1B:21:1C:8F:23  ";
        $mac2   = "00:1C:21:1C:8F:27  ";
        $sample = pack('H12', "001B211C8F23");

        $this->assertEquals($sample, $this->invokeMethod($this->wol, 'packMacAddress', [$mac1]));
        $this->assertNotEquals($sample, $this->invokeMethod($this->wol, 'packMacAddress', [$mac2]));
    }

    /**
     * @covers \WakeOnLAN\WakeOnLAN::buildMagicPacket()
     */
    public function testBuildMagicPacket()
    {
        $mac1   = "00:1B:21:1C:8F:23  ";
        $mac2   = "00:1C:21:1C:8F:27  ";
        $sample = str_repeat(chr(0xff), 6).str_repeat(pack('H12', "001B211C8F23"), 16);

        $this->assertEquals($sample, $this->invokeMethod($this->wol, 'buildMagicPacket', [$mac1]));
        $this->assertNotEquals($sample, $this->invokeMethod($this->wol, 'buildMagicPacket', [$mac2]));
    }

}