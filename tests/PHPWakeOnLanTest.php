<?php

namespace Diegonz\PHPWakeOnLan\Tests;

use Diegonz\PHPWakeOnLan\PHPWakeOnLan;
use PHPUnit\Framework\TestCase;

/**
 * Class PHPWakeOnLanTest
 *
 * @package Diegonz\PHPWakeOnLan\Tests
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
