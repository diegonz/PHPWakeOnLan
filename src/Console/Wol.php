<?php

namespace Diegonz\PHPWakeOnLan\Console;

use Diegonz\PHPWakeOnLan\Facades\PHPWakeOnLan;
use Illuminate\Console\Command;

class Wol extends Command
{
    /**
     * @var string
     */
    protected $signature = 'php-wake-on-lan:wake
                            {mac : Mac addresses string in XX:XX:XX:XX:XX:XX hexadecimal format. Only 0-9 and a-f are allowed}';

    /**
     * @var string
     */
    protected $description = 'Wake on lan target enabled device by sending magic packets to it from PHP';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(print_r(PHPWakeOnLan::wake([$this->argument('mac')]), true));

        return true;
    }
}
