<?php
require_once("config.php");
require_once("Item.php");

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error)
{
    fatalError($conn->connect_error);
    return;
}

/**
 * Queries the database for all shopping list items.
 *
 * For each result returned from the querry create a new Item and add to an array of Items.
 * Order the results returned by name.
 *
 * @param mysqli $conn A connection to a mysql database.
 *
 * @return a list of Items
 */
function getAllItems($conn)
{
    $results = array();
    $query = "SELECT name, price, quantity FROM shopping_list ORDER BY name;";
    $result = $conn->query($query);
    if (!$result)
    {
        fatalError($conn->error);
    }
    else {
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $results[] = new Item($row['name'], $row['price'], $row['quantity']);
        }

        $result->close();
    }
    return $results;
}

/**
 * Inserts an item into the shopping_list table.
 *
 * @param Item $item The item to insert into the database.
 * @param mysqli $conn A connection to a mysql database.
 */
function insertItem($item, $conn)
{
    $name = sanitizeInput($item->getName(), $conn);
    $price = sanitizeInput($item->getPrice(), $conn);
    $quantity = sanitizeInput($item->getQuantity(), $conn);

    $stmt = $conn->prepare("INSERT INTO shopping_list(name, price, quantity) VALUES(?,?,?)");
    $stmt->bind_param('sdd', $name, $price, $quantity);
    $stmt->execute();
    if ($stmt->errno)
    {
        fatalError($stmt->error);
    }
}

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

/**
 * Sanitizes an input string.
 *
 * Check to see if magic quotes are being used, if they are strip slashes from the input string.
 * Use the real_escape_string function of the database to escape the input string.
 * prevent XSS by calling htmlentities function on the input string.
 * Return the sanitized string.
 *
 * @param string $input The string to sanitize.
 * @param mysqli $conn A connection to a mysql database.
 *
 * @return a sanitized string.
 */
function sanitizeInput($input, $conn)
{
    if (get_magic_quotes_gpc())
    {
        $input = stripslashes($input);
    }
    $input = $conn->real_escape_string($input);
    return htmlentities($input);
}

/**
 * Echos an mysql error.
 *
 * @param string $error The error passed.
 */
function fatalError($error)
{
    $message = mysql_error();
    echo <<< _END
Something went wrong :/
<p>$error: $message</p>
_END;
}
 ?>
