<?php
	// Start a session before handling any html.
    session_name('arrays');
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Array's Form</title>
</head>
<body>
    <h1>Items</h1>
    <p>
        Submit items to be added to the shopping list
    </p>
    <form method='post' action='arrays_answers.php' enctype='multipart/form-data'>
        <label>
            New item:
            <input type='text' name='add' size='10'/>
        </label>
        <input type='submit' value='Add' />
    </form>
    <br>
    <form method="post" action="arrays_answers.php" enctype="multipart/form-data">
        <label>
            Remove item:
            <input type="text" name="remove" size=10/>
        </label>
    	<input type="submit" value="Remove" />
    </form>

	<h2>Shopping List</h2>
    <?php

        // If the PHP session has a shopping_list section,
        // copy its contents into the shopping_list PHP variable.
		if (isset($_SESSION['shopping_list']))
		{
			$shopping_list = $_SESSION['shopping_list'];
		}
        // Otherwise initialise the shopping_list PHP variable with these items.
		else
		{
			$shopping_list = array("milk", "bread", "oranges");
		}

        // If the page is requested in response to the POST request,
        // extract the values associated with the add field,
        // and insert them at the end of the shopping_list.
        if(isset($_POST['add']))
        {
        	$newItems = extractItems($_POST['add']);
        	$shopping_list = array_merge($newItems, $shopping_list);
        }

        // If the page is requested in response to the POST request,
        // extract the values associated with the remove field,
        // and remove them from the shopping_list.
        if(isset($_POST['remove']))
        {
        	$itemsToRemove = extractItems($_POST['remove']);
  			$shopping_list = removeAll($itemsToRemove, $shopping_list);
        }

        function removeAll($items, $array)
        {
        	foreach ($items as $item)
        	{
	            $index = findIndex($item, $array);
	        	if (!is_NULL($index))
	        	{
	        		$array= removeIndexFromArray($index, $array);
	        	}
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


        function printItems($array)
        {
            // print the items of the array using a for loop
        	// BEGIN STUDENT SECTION

        	for($i = 0; $i < count($array); $i++)
        	{
        		echo $array[$i] . "<br>";
        	}
        	// END STUDENT SECTION
        }

        function removeIndexFromArray($index, $array)
        {
        	array_splice($array, $index, 1);
        	return $array;
        }

        function findIndex($item, $array)
        {
        	// Find the index of an item in an array.
        	// Return NULL if the index is not found.
        	// BEGIN STUDENT SECTION

        	for($i = 0; $i < count($array); $i++)
        	{
        		if($item == $array[$i])
        		{
        			return $i;
        		}
        	}

        	return NULL;
        	// END STUDENT SECTION
        }

        printItems($shopping_list);

        $_SESSION['shopping_list'] = $shopping_list;
        session_write_close();
    ?>
</body>
</html>
