<?php

/**
 * Description of Net_Sharedmemory_View_Helper_DriverDetails
 *
 * @author david
 */
class Net_Sharedmemory_View_Helper_DriverDetails
{
    public function DriverDetails($driverId)
    {
        $driver = new Application_Model_Driver();
        $driverMapper = new Application_Model_DriverMapper();
        $driverMapper->find($driverId, $driver);

        return "{$driver->name} ({$driver->team})";
    }
}
