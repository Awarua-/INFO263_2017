<?php //shiplookup.php
include 'include/config.php';
include 'include/common.php';

if (isset($_POST['mmsi']))
{
    $mmsi = SanitizeString($_POST['mmsi']);
    $protocol = SanitizeString($_POST['protocol']);
    $msgtype = SanitizeString($_POST['msgtype']);
    $query = "http://services.marinetraffic.com/api/exportvessel/v:5/{$marineTrafficAPIKey}/msgtype:{$msgtype}/protocol:{$protocol}/mmsi:{$mmsi}";
    echo file_get_contents($query);
}
?>
