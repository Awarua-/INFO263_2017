 <?php
$active = "home";
require_once 'include/header.php';
?>

<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcd57Saz8czR6gtm6x5WhwAsopoN67jeU&callback=initMap"></script>
<script src="scripts/map.js"></script>

<body>
<div>
<div class="easyui-panel" title="Control Pane" style="width:1300px;height: 300px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true" style="width:800px;height: 280px;padding:10px">
            <table id = "data", class="easyui-datagrid" title="Route Table" style="width:750px;height:250px"
                   data-options="singleSelect:true,collapsible:true,url:'visittrips.php',method:'get'">
                <thead>
                <tr>
                    <th data-options="field:'route_short_name',width:80">short name</th>
                    <th data-options="field:'route_long_name',width:700,align:'right'">Route long name</th>
                </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'center'" style="padding:10px;width:450px;height:280px">
            <input class="easyui-textbox" id="log" data-options="multiline:true" value="" style="width:440px;height:230px;editable:false">
            </div>
        </div>
    </div>

<div id="googleMap" style="width:1300px;height:650px;"></div>
</div>
</body>

<script>

    $(function(){
        $("#log").textbox("setText","Log Info:\n");
    });
    var flag = false;
    var update_id = "";
    window.setInterval(updateData, 30 * 1000);
    function updateData()
    {
        if(flag == false)
        {
            return;
        }
        else if (update_id = "")
        {
            return;
        }
        else
            {
            setLog("Start to Update!");
            update(update_id);
            setLog("Finish Update!");
        }
    }

    function setLog(text)
    {
        var tmp = $("#log").textbox("getText");
        var time = new Date();
        var time_text= "";
        var hour = time.getHours();
        var mins = time.getMinutes();
        var secs = time.getSeconds();
        if(hour < 10)
        {
            hour = '0' + hour;
        }
        if(mins < 10)
        {
            mins = '0' + mins;
        }
        if(secs < 10)
        {
            secs = '0' + secs;
        }
        time_text = hour + ":" + mins + ":" + secs;
        //alert(tmp);
        $("#log").textbox("setText",tmp + time_text + "     "  + text + "\n");
    }
    $('#data').datagrid(
        {
        onClickRow: function(index,row){
            setLog(
                "Click datagrid on :" + row["route_short_name"]);
            update(row["route_short_name"]);
        }
    });

    function update(id)
    {
        points = [];
        data_response  = [];
        if(flag == false)
            flag = true;
        $.ajax(
            {
            type: "post",
            url: "sendtrip.php",
            data: {
                id:id
            },
            dataType: "json",
            success: function(msg)
            {
                setLog("Success get akl Data!");
                for(var i = 0 ; i < msg.length; i++)
                {
                    data_response[i] = [];
                    data_response[i] =  JSON.parse(msg[i]);
                }
                for(var i = 0 ; i < data_response.length; i++)
                {
                    var tmp  = data_response[i]["response"];
                    for(var j = 0 ; j < tmp["entity"].length; j++){
                        var tmp_v = tmp["entity"][j]["vehicle"]["position"];
                        //setFlag(tmp_v["latitude"], tmp_v["longitude"]);
                        points[j] = {};
                        points[j]["position"] = new google.maps.LatLng(tmp_v["latitude"], tmp_v["longitude"]);
                        points[j]["detail"] = tmp["entity"][j]["vehicle"];
                    }
                }
                setLog("Begin to call Google API!");
                var myOptions = {
                    zoom : 8,
                    center : new google.maps.LatLng(39.915, 116.404),
                    mapTypeId : google.maps.MapTypeId.ROADMAP
                };
                gmap = new google.maps.Map(document.getElementById("googleMap"),
                    myOptions);
                markers = [];
                setLog("FOUND Vehicle NUM：" + points.length);
                for(var i = 0;i<points.length;i++)
                {
                    var gmarker = new google.maps.Marker({
                        position : points[i]["position"],
                        map : gmap,
                        detail: points[i]
                    });
                    gmarker.addListener('click', function()
                    {
                        var tmp = this.detail["detail"];
                        var information = "";
                        information += "Vehicle ID:" + tmp["vehicle"]["id"] + "\r\n";
                        information += "Route ID:" + tmp["trip"]["route_id"] + "\n";
                        information += "Start time: " +tmp["trip"]["start_time"] + "\n";
                        information += "Trip ID: " + tmp["trip"]["trip_id"] + "\n";
                        alert(information);
                    });
                    markers.push(gmarker);
                }
                setVeiwPort();
                setLog("Success to call Google API!");
            }
        });
    }

</script>

<?php
require_once 'include/footer.php';
?>
