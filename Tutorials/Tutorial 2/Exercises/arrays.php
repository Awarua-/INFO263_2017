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
    <form method='post' action='arrays.php' enctype='multipart/form-data'>
        <label>
            New item:
            <input type='text' name='add' size='10'/>
        </label>
        <input type='submit' value='Add' />
    </form>

    <!-- Add Remove form here.-->

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
        	$shopping_list[] = $_POST['add'];
        }

        function printItems($array)
        {
        	// Print the items of the array using a for loop.
        	// BEGIN STUDENT SECTION

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

        	return 0; // Remove

        	// END STUDENT SECTION
        }


        printItems($shopping_list);

        $_SESSION['shopping_list'] = $shopping_list;
        session_write_close();
    ?>
</body>
</html>
