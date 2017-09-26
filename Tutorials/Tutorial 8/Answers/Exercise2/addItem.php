<?php
require_once("database.php");

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
    echo $name;
    $stmt = $conn->prepare("INSERT INTO shopping_list(name, price, quantity) VALUES(?,?,?)");
    $stmt->bind_param('sdd', $name, $price, $quantity);
    $stmt->execute();
    if ($stmt->errno)
    {
        fatalError($stmt->error);
    }
}

if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $item = new Item($name, $price, $quantity);
    insertItem($item, $conn);
}
 ?>
