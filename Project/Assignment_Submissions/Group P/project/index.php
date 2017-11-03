<?php
$active = "home";
require_once 'include/header.php';
//require_once 'include/database.php';
?>
<?php
$active = "home";
require_once 'include/header.php';
//require_once 'include/database.php';
?>
<!DOCTYPE html>
<html>

<div id="map"></div>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyChfhgDhk_M6bxQlXY51QBLL0V3GGfltIg&callback=initMap">
</script>
<script src="scripts/map.js"></script>
<script src="scripts/markers.js"></script>

<script>
    $(document).ready(function() {
    });
</script>


<form name="resources" action="include/database.php"  method="post" >
    Select Route:
    <?php
    echo "<select name='routes'>";
    while($row = mysqli_fetch_array($result))
    {
        echo $result->fetch_assoc()['route_short_name'] . '<br>'; //"$row[0]<br>";
    }
    echo "</select>";
    ?>
</form>

<?php
require_once 'include/footer.php';
?>
</html>
