<?php // common.php
function fatalError($error)
{
    $message = mysql_error();
    echo <<< _END
Something went wrong :/
<p>$error: $message</p>
_END;
}

function sanitizeString($string)
{
    $dirty = strip_tags($dirty);
    $dirty = htmlentities($dirty);
    return stripslashes($dirty); 
}

function mysqlSanitise($conn, $string)
{
    if (get_magic_quotes_gpc())
    {
        $string = stripslashes($string);
    }
    $string = htmlentities($string);
    return $conn->real_escape_string($string);
}
?>
