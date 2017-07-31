<?php
$active = "ports";
$title = "Ports";
include 'include/header.php';
?>

<div id="ports">

</div>
<script>
    var params = { query: "ports" };
    $.post("query.php", params, function(data) {
        var ports = JSON.parse(data);
        var content = "";
        for (var port of ports) {
            content += port.name + '</br>';
        }
        $("#ports").html(content);
    });
</script>

<?php
include 'include/footer.php';
?>
