# PHPWakeOnLan

![](https://travis-ci.com/diegonz/PHPWakeOnLan.svg?branch=master)
![StyleCI](https://github.styleci.io/repos/128269954/shield?branch=master)

Wake on lan target enabled devices by sending magic packets to them from PHP.

## Installation

Require the package using [composer](https://getcomposer.org/).

```bash
composer require diegonz/php-wake-on-lan
```

## Usage:

```php
<?php

use \Diegonz\PHPWakeOnLan\WakeOnLan;

$macAddresses = [
    '00:1B:2C:1C:DF:22',
    '01:1C:2C:1C:DF:13',
];

try {
    $wol = new WakeOnLan($macAddresses);
    print_r($wol->wake());
} catch (Exception $e) {
    var_dump($e->getMessage());
}
```

Example output:

```bash
Array
(
    [00:1B:2C:1C:DF:22] => Array
        (
            [result]     => OK
            [message]    => Magic packet sent to 00:1B:2C:1C:DF:22 through 255.255.255.255
            [bytes_sent] => 102
        )

    [01:1C:2C:1C:DF:13] => Array
        (
            [result]     => OK
            [message]    => Magic packet sent to 01:1C:2C:1C:DF:13 through 255.255.255.255
            [bytes_sent] => 102
        )
)
```

## External links

[Magic Packet Technology](http://support.amd.com/TechDocs/20213.pdf) -
White paper describing the specification and implementation of Magic Packet™
technology from AMD, one of its two co-developers.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
The MIT License ([MIT](./LICENSE.md)). Please see license file for more information.
