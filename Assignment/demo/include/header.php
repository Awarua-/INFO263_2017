<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php if (isset($title)){echo "$title - ";} ?>INFO263 Demo Project</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <link rel="stylesheet" href="css/master.css" media="screen" title="no title" charset="utf-8">
        <link rel="stylesheet" href="css/mobile.css" media="screen and (max-width: 700px)" title="no title" charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha384-xBuQ/xzmlsLoJpyjoggmTEz8OWUFM0/RC5BsqQBDX2v5cMvDHcMakNTNrHIW2I5f" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js" integrity="sha384-FzT3vTVGXqf7wRfy8k4BiyzvbNfeYjK+frTVqZeNDFl8woCbF0CYG6g2fMEFFo/i" crossorigin="anonymous"></script>
    </head>
    <body <?php if($active == 'home') {echo 'id="home"';} ?>>
        <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
            <a class="navbar-brand" href="index.php" <?php if($active == 'home') {echo "class='active'";} ?>>INFO263</a>
            </nav>

            <div class="container">
