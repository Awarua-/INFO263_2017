<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php if (isset($title)){echo "$title - ";} ?>Inteli Bus ++</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <link rel="stylesheet" href="css/master.css" media="screen" title="no title" charset="utf-8">
		<link rel="icon" href="media/bus.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha384-xBuQ/xzmlsLoJpyjoggmTEz8OWUFM0/RC5BsqQBDX2v5cMvDHcMakNTNrHIW2I5f" crossorigin="anonymous"></script>
    </head>
    <body <?php if($active == 'home') {echo 'id="home"';} ?>>
        <nav id="fixednav" class="navbar navbar-toggleable-md navbar-inverse fixed-top">
            <a class="navbar-brand" href="index.php" <?php if($active == 'home') {echo "class='active'";} ?>><table><tr><td><h2>Inteli Bus ++</h2></td></tr><tr><td> <div id= "micro">Inteli Bus with classes</div></td></tr></table></a>
            </nav>

            <div class="container">
