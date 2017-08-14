<?php // common.php
function sanitizeString($dirty)
{
    $dirty = strip_tags($dirty);
    $dirty = htmlentities($dirty);
    return stripslashes($dirty);
}

function fatalError($error)
{
    $message = mysql_error();
    echo <<< _END
Something went wrong :/
<p>$error: $message</p>
_END;
}

function mysqlSanitise($conn, $string)
{
    $string = sanitizeString($string);
    if (get_magic_quotes_gpc())
    {
        $string = stripslashes($string);
    }
    return $conn->real_escape_string($string);
}
?>
