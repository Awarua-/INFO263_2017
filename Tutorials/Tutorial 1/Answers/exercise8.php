<html>
<head>
    <title>PHP Form Upload</title>
</head>
<body>
    <h1>File Upload Form</h1>
    <p>
        Click the 'Choose File' button to select a file to upload.
    </p>
    <form method='post' action='exercise8.php' enctype='multipart/form-data'>
        Select File: <input type='file' name='filename' size='10' />
        <input type='submit' value='Upload' />
    </form>

    <?php

        if($_FILES)
        {
            $valid = true;
            switch($_FILES['filename']['type'])
            {
                case "image/jpeg":
                    break;
                case "image/gif":
                    break;
                case "image/png":
                    break;
                case "image/tiff":
                    break;
                default:
                    $valid = false;
            }

            $name = $_FILES['filename']['name'];
            if ($valid)
            {
                move_uploaded_file($_FILES['filename']['tmp_name'], $name);
                echo "Uploaded image '$name'<br /><img src='$name'/>";
            }
            elseif (!file_exists($_FILES['filename']['tmp_name']) || !is_uploaded_file($_FILES['filename']['tmp_name'])) {
                echo "No file was uploaded.";
            }
            else
            {
                echo "Uploaded file: '$name' was not a supported image.";
            }
        }
    ?>
</body>
</html>
