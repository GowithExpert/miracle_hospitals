<?php 
// app/Libraries/CustomSessionHandler.php

namespace App\Libraries;

use CodeIgniter\Session\Handlers\DatabaseHandler;

class CustomSessionHandler extends DatabaseHandler
{
    protected $deviceIdentifier;

    public function __construct($config)
    {
        parent::__construct($config);

        // Get the device identifier (you may use something like user-agent or IP address)
        $this->deviceIdentifier = // logic to get device identifier;
    }

    public function read($sessionID)
    {
        $sessionData = parent::read($sessionID);

        // Check if the device identifier matches the stored identifier
        if (!empty($sessionData['device_identifier']) && $sessionData['device_identifier'] !== $this->deviceIdentifier) {
            // Invalidate the session for this device
            $this->destroy($sessionID);
            $sessionData = [];
        }

        return $sessionData;
    }

    public function write($sessionID, $sessionData)
    {
        // Associate the device identifier with the session data
        $sessionData['device_identifier'] = $this->deviceIdentifier;

        return parent::write($sessionID, $sessionData);
    }
}

?>