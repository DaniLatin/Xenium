<?php

/**
 * @author Danijel
 * @copyright 2013
 */



?>
<div class="custom-container title-container">
    <h3>Applications exports</h3>
    <p>Data exports offered by applications</p>
</div>

<div class="custom-container">
    <h4>Payments for Navision</h4>
    <p><label>Daterange:</label>
        <div class="input-append">
            <input id="export_daterange" class="input-large daterange" type="text" />
            <span class="input-group-btn">
                <button id="navision_export" class="btn btn-default" type="button">Export</button>
            </span>
        </div>
        <a id="export_invisible" class="invisible" target="_self">export</a>
    </p>
</div>

<script type="text/javascript">
    $("#navision_export").click(function(){
        var daterange = $("#export_daterange").val();
        if (daterange){
            $("#export_invisible").attr('href', '/admin/system/on.the.fly/navision.xml.php?daterange=' + daterange);
            $("#export_invisible")[0].click();
        } else {
            bootbox.alert("Please select a daterange!");
        }
    });
</script>