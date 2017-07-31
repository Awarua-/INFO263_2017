<?php
	// Start a session before handling any html.
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Array's Form</title>
</head>
<body>
    <h1>Groceries</h1>
    <p>
        Submit items to be added to the shopping list
    </p>
    <form method='post' action='arrays_answers.php' enctype='multipart/form-data'>
        New item: <input type='text' name='add' size='10' />
        <input type='submit' value='Add' />
    </form>
    <span></span>
    <form method="post" action="arrays_answers.php" enctype="multipart/form-data">
    	Remove item: <input type="text" name="remove" size=10 />
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
			$groceries = array("milk", "bread", "oranges");
		}

        if(isset($_POST['add']))
        {
        	$newItems = extractItems($_POST['add']);
        	$groceries = array_merge($newItems, $groceries);
        }

        if(isset($_POST['remove']))
        {
        	$itemsToRemove = extractItems($_POST['remove']);
  			$groceries = removeAll($itemsToRemove, $groceries);
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
        		echo $array[$i] . "</br>";
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

        $_SESSION['groceries'] = $groceries;
        printItems($groceries);
    ?>
</body>
</html>
