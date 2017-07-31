<?php
	require_once("Item.php");
	// Start a session before handling any html.
    session_name('classes');
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Classes Form</title>
</head>
<body>
    <h1>Groceries</h1>
    <p>
        Submit items to be added to the shopping list
    </p>
    <form method='post' action='classes.php' enctype='multipart/form-data'>
        New item:<br>
            <input type='hidden' name='add' value='1' />
        <label>
            Name:
            <input type='text' name='name' size='10'/>
        </label>
			<!-- add price and quantity form parameters. -->
        <input type='submit' value='Add' />
    </form>
    <br>
    <form method="post" action="classes.php" enctype="multipart/form-data">
        <label>
            Remove item:
            <input type="text" name="remove" size=10/>
        </label>
    	<input type="submit" value="Remove" />
    </form>

	<h2>Shopping List</h2>
    <?php

		if (isset($_SESSION['groceries']))
		{
			$groceries = $_SESSION['groceries'];
		}
		else
		{
            $milk = new Item("milk", 2.00, 1);
            $bread = new Item("bread", 2.50, 2);
			$groceries = array($milk->get_name() => $milk,
                                $bread->get_name() => $bread);
		}

        if(isset($_POST['add']))
        {
        	$newItem = createItem($_POST);
            if (!is_null($newItem))
            {
        	       $groceries[$newItem->get_name()] = $newItem;
            }
        }

        if(isset($_POST['remove']))
        {
        	$itemsToRemove = extractItems($_POST['remove']);
  			$groceries = removeAll($itemsToRemove, $groceries);
        }

        if(isset($_POST['clear']))
        {
            $groceries = array();
        }

        function removeAll($items, $array)
        {
        	foreach ($items as $item)
        	{
	            unset($array["{$item}"]);
        	}
        	return $array;
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

        function createItem($item)
        {
        	return new Item($item['name'], $item['price'], $item['quantity']);
			//Extend later
        }


        function printItems($array)
        {
        	echo "<pre>"; //Leave for display method to print properly

			for($i = 0; $i < count($array); $i++)
        	{
        		//echo $array[$i] . "</br>";
        	}

        	echo "</pre>"; //Leave for display method to print properly
        }

        printItems($groceries);

        $_SESSION['groceries'] = $groceries;
        session_write_close();
    ?>
</body>
</html>
