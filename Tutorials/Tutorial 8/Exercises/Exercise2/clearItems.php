<?php
require_once("database.php");


/**
 * Clears all items in the shopping_list table.
 *
 * @param mysqli $conn A connection to a mysql database.
 */
function clearShoppingList($conn)
{
    $query = "TRUNCATE shopping_list;";
    $result = $conn->query($query);
    if (!$result)
    {
        fatalError($conn->error);
    }
}

clearShoppingList($conn);
 ?>
