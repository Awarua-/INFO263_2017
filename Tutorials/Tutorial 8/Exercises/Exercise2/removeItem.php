<?php
require_once("database.php");

/**
 * Delete all items with a give item name.
 *
 * @param string $item_name The name of the item(s) to remove.
 * @param mysqli $conn A connection to a mysql database.
 */
function deleteItem($item_name, $conn)
{
    $name = sanitizeInput($item_name, $conn);
    $stmt = $conn->prepare("DELETE FROM shopping_list WHERE name = ?;");
    $stmt->bind_param('s', $name);
    $stmt->execute();
    if ($stmt->errno)
    {
        fatalError($stmt->error);
    }
}

if (isset($_POST['name'])) {
    deleteItem($_POST['name'], $conn);
}
 ?>
