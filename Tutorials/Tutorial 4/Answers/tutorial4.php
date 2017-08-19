<?php
	require_once("database.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping List</title>
</head>
<body>
    <h1>Items</h1>
    <p>
        Submit items to be added to the shopping list
    </p>
    <form method='post' action='tutorial4.php' enctype='multipart/form-data'>
        New item:<br>
            <input type='hidden' name='add' value='1' />
        <label>
            Name:
            <input type='text' name='name' size='10'/>
        </label>
        <label>
            Price: $
            <input type='number' name='price' size='5' value='1' step='any'/>
        </label>
        <label>
            Quantity:
            <input type='number' name='quantity' value="1" size='5' step='any'/>
        </label>

        <input type='submit' value='Add' />
    </form>
    <br>
    <form method="post" action="tutorial4.php" enctype="multipart/form-data">
        <label>
            Remove items:
            <input type="text" name="remove" size=10/>
        </label>
    	<input type="submit" value="Remove" />
    </form>

    <br>
<form method="post" action="tutorial4.php" enctype="multipart/form-data">
    <input type="hidden" name="clear" value="1" />
    <input type="submit" value="Clear All" />
</form>

	<h2>Shopping List</h2>
    <?php

        if(isset($_POST['add']))
        {
        	$item = createItem($_POST);

			if (!is_null($item))
			{
				insertItem($item, $conn);
			}
        }

        if(isset($_POST['remove']))
        {
	        deleteItems(extractItems($_POST['remove']), $conn);
        }

        if(isset($_POST['clear']))
        {
            clearShoppingList($conn);
        }

        function extractItems($itemsString)
        {
            $items = explode(',', $itemsString);
            for ($i = 0; $i < count($items); $i++)
            {
                $items[$i] = trim($items[$i]);
            }
            return $items;
        }

		function deleteItems($items, $conn)
		{
		    foreach($items as $item)
		    {
		        deleteItem($item, $conn);
		    }
		}

        function createItem($item)
        {
            if (trim($item['name']) !== '' && $item['name'] !== NULL)
            {
        	    return new Item($item['name'], $item['price'], $item['quantity']);
            }
            return NULL;
        }

        function printItems($array)
        {
        	echo "<pre>";
            $total = 0;
        	foreach ($array as $item)
            {
                echo $item->display() . "<br>";
                $total += $item->calculateCost();
            }

            printf("</br>Total: $%-6.2f", $total);
        	echo "</pre>";
        }

		$shopping_list = getAllItems($conn);
        printItems($shopping_list);

		$conn->close();
    ?>
</body>
</html>
