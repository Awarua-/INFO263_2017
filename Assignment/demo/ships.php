<?php
$active = "ships";
$title = "Ships";
include 'include/header.php';
?>
<div id="ships">

</div>
<script>
    var params = { query: "ships" };
    $.post("query.php", params, function(data) {
        var ships = JSON.parse(data);
        var content = "";
        for (var ship of ships) {
            content += ship['Vessel Name'] + '</br>';
        }
        $("#ships").html(content);
    });
</script>

<?php
include 'include/footer.php';
?>
