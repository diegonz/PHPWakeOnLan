<?php

namespace Diegonz\PHPWakeOnLan\Tests;

use Diegonz\PHPWakeOnLan\WakeOnLan;
use PHPUnit\Framework\TestCase;

/**
 * Class WakeOnLanTest
 *
 * @covers  \Diegonz\PHPWakeOnLan\WakeOnLan
 *
 * @package Diegonz\PHPWakeOnLan\TestCases
 */
class WakeOnLanTest extends TestCase
{

    /**
     * @covers WakeOnLan::isBroadcastAddressValid()
     */
    public function testIsBroadcastAddressValid()
    {
        $this->assertTrue(WakeOnLan::isBroadcastAddressValid('192.168.1.255'));
        $this->assertFalse(WakeOnLan::isBroadcastAddressValid('192.168.1.33'));
    }
}
