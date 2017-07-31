<?php
	require_once("Item_answers.php");
    require_once("ItemQuantised_answers.php");
	// Start a session before handling any html.
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
    <form method='post' action='classes_answers.php' enctype='multipart/form-data'>
        New item:</br>
            <input type='hidden' name='add' value='1' />
            Name: <input type='text' name='name' size='10' />
            Price: $<input type='number' name='price' size='5' value='1' step='any' />
            Quantity: <input type='number' name='quantity' value="1" size='5' step='any'/>
            <?php
                $refl = new ReflectionClass('ItemQuantised');
                $types = $refl->getConstants();
                foreach($types as $name => $symbol)
                {
                    echo "<input type='radio' name='type' value='{$name}' /> {$symbol}";
                }
            ?>

        <input type='submit' value='Add' />
    </form>
</br>
    <form method="post" action="classes_answers.php" enctype="multipart/form-data">
    	Remove item: <input type="text" name="remove" size=10 />
    	<input type="submit" value="Remove" />
    </form>

</br>
<form method="post" action="classes_answers.php" enctype="multipart/form-data">
    <input type="hidden" name="clear" value="1" />
    <input type="submit" value="Clear All" />
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
            $oranges = new ItemQuantised("oranges", 1.80, 2.00, ItemQuantised::KILOGRAM);
			$groceries = array($milk->get_name() => $milk,
                                $bread->get_name() => $bread,
                                $oranges->get_name() => $oranges);
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
            if (trim($item['name']) !== '' && $item['name'] !== NULL)
            {
                if(isset($item['type']))
                {
                    return new ItemQuantised($item['name'], $item['price'], $item['quantity'], $item['type']);
                }
                else
                {
        	       return new Item($item['name'], $item['price'], $item['quantity']);
                }
            }
            return NULL;
        }


        function printItems($array)
        {
            // print the items of the array using a for loop
        	// BEGIN STUDENT SECTION
        	echo "<pre>";
            $total = 0;
        	foreach ($array as $item)
            {
                echo $item->display() . "</br>";
                $total += $item->calculate_cost();
            }

            printf("</br>Total: $%-6.2f", $total);
        	echo "</pre>";
        	// END STUDENT SECTION
        }

        $_SESSION['groceries'] = $groceries;
        printItems($groceries);
    ?>
</body>
</html>
