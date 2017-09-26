<?php
require_once("database.php");


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

$result = getAllItems($conn);
header('Content-Type: application/json');
print(json_encode($result));

?>
