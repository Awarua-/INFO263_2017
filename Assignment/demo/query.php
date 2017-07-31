<?php //query.php
include 'include/common.php';
include 'include/config.php';

if (isset($_POST['query']))
{
    $query = SanitizeString($_POST['query']);
    if ($query == "ships")
    {
        echo $shipsJson;
    }
    elseif ($query == "ports")
    {
        echo $portsJson;
    }
}
?>
