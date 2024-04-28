<?php

namespace Diegonz\PHPWakeOnLan\Tests;

use Diegonz\PHPWakeOnLan\CidrNetwork;
use PHPUnit\Framework\TestCase;
use Diegonz\PHPWakeOnLan\PHPWakeOnLan;

/**
 * Class PHPWakeOnLanTest.
 *
 * @covers \Diegonz\PHPWakeOnLan\PHPWakeOnLan
 */
class PHPWakeOnLanTest extends TestCase
{
    /**
     * @covers \Diegonz\PHPWakeOnLan\PHPWakeOnLan::isBroadcastAddressValid()
     */
    public function testIsBroadcastAddressValid(): void
    {
        $this->assertTrue(PHPWakeOnLan::isBroadcastAddressValid('192.168.1.255'));
        $this->assertFalse(PHPWakeOnLan::isBroadcastAddressValid('192.168.1.33'));
    }

    /**
     * @covers \Diegonz\PHPWakeOnLan\PHPWakeOnLan::wake()
     * @throws \Exception
     */
    public function testWake(): void
    {
        $wol = new PHPWakeOnLan();
        $result = $wol->wake(['00:1B:2C:1C:DF:22']);

        //$this->assertTrue(is_array($result));
        $this->assertNotEmpty($result);
    }

    public function testWakeWithCidrNetwork(): void
    {
        /**
         * Network address: 192.168.50.0
         * Broadcast Address: 192.168.50.3
         */
        $cidrNetwork = CidrNetwork::make('192.168.50.1', '255.255.255.252');

        $wol = new PHPWakeOnLan();
        $result = $wol->wake(['00:1B:2C:1C:DF:22'], $cidrNetwork);

        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('message', $result);
        $this->assertSame(
            "Magic packet sent to 00:1B:2C:1C:DF:22 through 192.168.50.3",
            $result['message']
        );
    }
}
