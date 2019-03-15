<?php

namespace Diegonz\PHPWakeOnLan\Tests;

use PHPUnit\Framework\TestCase;
use Diegonz\PHPWakeOnLan\PHPWakeOnLan;

/**
 * Class PHPWakeOnLanTest.
 */
class PHPWakeOnLanTest extends TestCase
{
    /**
     * @covers \Diegonz\PHPWakeOnLan\PHPWakeOnLan::isBroadcastAddressValid()
     */
    public function testIsBroadcastAddressValid()
    {
        $this->assertTrue(PHPWakeOnLan::isBroadcastAddressValid('192.168.1.255'));
        $this->assertFalse(PHPWakeOnLan::isBroadcastAddressValid('192.168.1.33'));
    }
}
