<?php

namespace Diegonz\PHPWakeOnLan\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class WakeOnLan
 *
 * @package Diegonz\PHPWakeOnLan\Facades
 */
class PHPWakeOnLan extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'php-wake-on-lan';
    }
}
