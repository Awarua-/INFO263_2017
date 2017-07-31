<?php // common.php
function SanitizeString($dirty)
{
    $dirty = strip_tags($dirty);
    $dirty = htmlentities($dirty);
    return stripslashes($dirty);
}
?>
