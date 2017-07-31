<html>
<head>

</head>
<body>
    <?php
        function FizzBuzz($int)
        {
            $str = "";
            if (!($int % 3))
            {
                $str .= "Fizz";
            }

            if (!($int % 5))
            {
                $str .= "Buzz";
            }
            return $str;
        }
        echo FizzBuzz(30) . "<br/>";
        echo FizzBuzz(4) . "<br/>";
        echo FizzBuzz(6) . "<br/>";
        echo FizzBuzz(10) . "<br/>";
    ?>
</body>
</html>
