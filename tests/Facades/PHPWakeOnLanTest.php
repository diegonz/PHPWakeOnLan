<?php

namespace Diegonz\PHPWakeOnLan\Tests\Facades;

use Diegonz\PHPWakeOnLan\Facades\PHPWakeOnLan;
use PHPUnit\Framework\TestCase;

/**
 * Class PHPWakeOnLanTest.
 *
 * @covers \Diegonz\PHPWakeOnLan\Facades\PHPWakeOnLan
 */
class PHPWakeOnLanTest extends TestCase
{
    /**
     * @covers \Diegonz\PHPWakeOnLan\Facades\PHPWakeOnLan::getFacadeAccessor()
     */
    public function testGetFacadeAccessor(): void
    {
        PHPWakeOnLan::shouldReceive('getFacadeAccessor')->once()->andReturn('php-wake-on-lan');

        $this->assertEquals('php-wake-on-lan', PHPWakeOnLan::__callStatic('getFacadeAccessor', []));
    }
}
