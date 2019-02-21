<?php

namespace Diegonz\PHPWakeOnLan\TestCases;

use Diegonz\PHPWakeOnLan\WakeOnLan;

class TestCase extends \PHPUnit\Framework\TestCase
{

    /**
     * @var WakeOnLan Public instance for testing purposes
     */
    public $wol;

    /**
     * WakeOnLANTestCase constructor.
     *
     * Creates public PHPWakeOnLan instance for testing purposes
     */
    public function __construct()
    {
        parent::__construct();
        $this->wol = new WakeOnLan();
    }

    /**
     * Call a protected/private method of a class through reflection.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call on target object
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, string $methodName, array $parameters = [])
    {
        try {
            $reflection = new \ReflectionClass(get_class($object));
            $method     = $reflection->getMethod($methodName);
            $method->setAccessible(true);

            $result = $method->invokeArgs($object, $parameters);
        } catch (\ReflectionException $e) {
            var_dump($e->getMessage());
        }

        return empty($result) ? null : $result;
    }

}
