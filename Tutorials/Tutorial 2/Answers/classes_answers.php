<?php
	require_once("Item_answers.php");
    require_once("ItemQuantised_answers.php");
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
    <h1>Items</h1>
    <p>
        Submit items to be added to the shopping list
    </p>
    <form method='post' action='classes_answers.php' enctype='multipart/form-data'>
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
		<label>
			Type:
            <?php
            // This is magic, don't worry about if you have no idea how it works.
                $refl = new ReflectionClass('ItemQuantised');
                $types = $refl->getConstants();
                foreach($types as $name => $symbol)
                {
                    echo "<input type='radio' name='type' value='{$name}' /> {$symbol}";
                }
            ?>
		</label>

        <input type='submit' value='Add' />
    </form>
    <br>
    <form method="post" action="classes_answers.php" enctype="multipart/form-data">
        <label>
            Remove item:
            <input type="text" name="remove" size=10/>
        </label>
    	<input type="submit" value="Remove" />
    </form>

    <br>
<form method="post" action="classes_answers.php" enctype="multipart/form-data">
    <input type="hidden" name="clear" value="1" />
    <input type="submit" value="Clear All" />
</form>

	<h2>Shopping List</h2>
    <?php

		if (isset($_SESSION['shopping_list']))
		{
			$shopping_list = $_SESSION['shopping_list'];
		}
		else
		{
            $milk = new Item("milk", 2.00, 1);
            $bread = new Item("bread", 2.50, 2);
            $oranges = new ItemQuantised("oranges", 1.80, 2.00, ItemQuantised::KILOGRAM);
			$shopping_list = array($milk->get_name() => $milk,
                                $bread->get_name() => $bread,
                                $oranges->get_name() => $oranges);
		}

        if(isset($_POST['add']))
        {
        	$newItem = createItem($_POST);
            if (!is_null($newItem))
            {
        	       $shopping_list[$newItem->get_name()] = $newItem;
            }
        }

        if(isset($_POST['remove']))
        {
        	$itemsToRemove = extractItems($_POST['remove']);
  			$shopping_list = removeAll($itemsToRemove, $shopping_list);
        }

        if(isset($_POST['clear']))
        {
            $shopping_list = array();
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
                echo $item->display() . "<br>";
                $total += $item->calculate_cost();
            }

            printf("</br>Total: $%-6.2f", $total);
        	echo "</pre>";
        	// END STUDENT SECTION
        }


        printItems($shopping_list);

        $_SESSION['shopping_list'] = $shopping_list;
        session_write_close();
    ?>
</body>
</html>
