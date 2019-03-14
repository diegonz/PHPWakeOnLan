<?php

namespace Diegonz\PHPWakeOnLan\Http\Controllers;

use Diegonz\PHPWakeOnLan\Facades\PHPWakeOnLan;

/**
 * Class PHPWakeOnLanController
 *
 * @package Diegonz\PHPWakeOnLan\Http\Controllers
 */
class PHPWakeOnLanController
{

    /**
     * @param string $macAddress
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke(string $macAddress)
    {
        return view('php-wake-on-lan::result', [
            'result' => PHPWakeOnLan::wake([$macAddress]),
        ]);
    }
}
