<?php //shiplookup.php
require_once 'include/config.php';
require_once 'include/common.php';

if (isset($_POST['type']) && $_POST['type'] === 'realtime')
{
    $vehicle_id = SanitizeString($_POST['vehicle_id']);


    $opts = array(
        'http' => array(
          'header'=>"Ocp-Apim-Subscription-Key: ${APIKey}"
        )
    );

    $context = stream_context_create($opts);

    $query = "https://api.at.govt.nz/v2/public/realtime/?vehicleid={$vehicle_id}";
    echo file_get_contents($query, false, $context);
}
?>
