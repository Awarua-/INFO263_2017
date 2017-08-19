<?php //shiplookup.php
require_once 'include/config.php';
require_once 'include/common.php';
require_once 'include/requests.php';

if (isset($_POST['type']) && $_POST['type'] === 'realtime')
{
    $vehicle_id = SanitizeString($_POST['vehicle_id']);
    $query = "https://api.at.govt.nz/v2/public/realtime/?vehicleid={$vehicle_id}";

    echo apiCall($APIKey, $query);
}
?>
