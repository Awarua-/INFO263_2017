<?php
function prepraredQuery($conn, $query, $format, $values)
{
    if (!is_null($query))
    {
        $stmt = $conn->prepare($query);
        if ($stmt->errno)
        {
            fatalError($stmt->error);
            return;
        }
        $bind_arguments = [];
        $bind_arguments[] = $format;
        foreach ($values as $recordkey => $recordvalue)
        {
            $bind_arguments[] = & $recordvalues[$recordkey];    # bind to array ref, not to the temporary $recordvalue
        }
        // array_unshift($values, $format);
        // call_user_func_array(array($stmt, 'bind_param'), $bind_arguments);
        $stmt->bind_param($format, ...$values);
        $stmt->execute();
        if ($stmt->errno)
        {
            fatalError($stmt->error);
            return;
        }
        else {
            $result = $stmt->get_result();
            $results = array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $results[] = $row;
            }
            $stmt->close();

            return $results;
        }
    }
}

function query($conn, $query)
{
    if (!is_null($query))
    {
        $result = $conn->query($query);
        if (!$result)
        {
            echo $conn->error;
        }
        else {
            $results = array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $results[] = $row;
            }

            return $results;
        }
    }
}
 ?>
