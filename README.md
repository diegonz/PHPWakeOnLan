# PHPWakeOnLan

Wake on lan target enabled devices by sending magic packets to them from PHP.
Send magic packet to one or more target mac addresses through broadcast address.

## Example usage:

```php
<?php

require './vendor/autoload.php';

use \Diegonz\PHPWakeOnLan\WakeOnLan;

$macAddresses     = [
    '00:1B:2C:1C:DF:22',
    '01:1C:2C:1C:DF:13',
];

try {
    $wol = new WakeOnLan();
    print_r($wol->wake($macAddresses));
} catch (Exception $e) {
    var_dump($e->getMessage());
}
```

### Example execution output:

```
Array
(
    [00:1B:2C:1C:DF:22] => Array
        (
            [result] => OK
            [message] => Magic packet sent to 00:1B:2C:1C:DF:22 through 255.255.255.255
            [bytes_sent] => 102
        )

    [01:1C:2C:1C:DF:13] => Array
        (
            [result] => OK
            [message] => Magic packet sent to 01:1C:2C:1C:DF:13 through 255.255.255.255
            [bytes_sent] => 102
        )
)
```

### External links

[Magic Packet Technology](http://support.amd.com/TechDocs/20213.pdf) - 
White paper describing the specification and implementation of Magic Packet™ 
technology from AMD, one of its two co-developers.
