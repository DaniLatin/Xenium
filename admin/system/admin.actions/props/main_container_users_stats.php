<?php

/**
 * @author Danijel
 * @copyright 2013
 */

global $set_period, $users_stats, $today_count, $today_activated, $week_count, $week_activated, $month_count, $month_activated;

?>
<div class="custom-container title-container">
    <h3>Users statistics</h3>
    <p>Statistics of registered / activated users, registration types and profile updates</p>
</div>

<div class="custom-container">
    <div>
        <div style="float: left;">
            <!--<h4>Users statistics</h4>-->
            <h5>Show stats by:</h5>
            <select id="select_period" onchange="change_period()">
                <option value="day" <?php if ($set_period == 'day'){ ?>selected="selected"<?php } ?>>days</option>
                <option value="week" <?php if ($set_period == 'week'){ ?>selected="selected"<?php } ?>>weeks</option>
                <option value="month" <?php if ($set_period == 'month'){ ?>selected="selected"<?php } ?>>months</option>
            </select>
            <a id="redirect_stats" class="follow invisible"></a>
        </div>
        <div style="float: right;" class="well stats-well">
            <h5>THIS MONTH:</h5>
            <p>Registered: <strong><?php echo $month_count; ?></strong></p>
            <p>Activated: <strong><?php echo $month_activated; ?></strong></p>
        </div>
        <div style="float: right;" class="well stats-well">
            <h5>THIS WEEK:</h5>
            <p>Registered: <strong><?php echo $week_count; ?></strong></p>
            <p>Activated: <strong><?php echo $week_activated; ?></strong></p>
        </div>
        <div style="float: right;" class="well stats-well">
            <h5>TODAY:</h5>
            <p>Registered: <strong><?php echo $today_count; ?></strong></p>
            <p>Activated: <strong><?php echo $today_activated; ?></strong></p>
        </div>
        <div class="clear"></div>
    </div>
    <ul id="charts-tabs" class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" no-follow="true" href="#registrations" style="text-align: center;">Registrations</a></li>
        <li><a data-toggle="tab" no-follow="true" href="#registrations_type" style="text-align: center;">Registrations by registration types</a></li>
        <li><a data-toggle="tab" no-follow="true" href="#social_updates" style="text-align: center;">Number of profile updates by social logins</a></li>
    </ul>
    <div id="charts-tabsContent" class="tab-content charts-container">
        <div id="registrations" class="chart tab-pane fade active in">
            
                <div class="cover"></div>
                <div id="chart_registrations" class="time-chart" style="height: 400px;" mainchart="counter_activated" charts="counter_activated,counter_registered"></div>
            
        </div>
        <div id="registrations_type" class="chart tab-pane fade active out">
            
                <div class="cover"></div>
                <div id="chart_numbers" class="time-chart" style="height: 400px;" charts="counter_classic_registered,counter_fb_registered,counter_google_registered"></div>
            
        </div>
        <div id="social_updates" class="chart tab-pane fade active out">
            
                <div class="cover"></div>
                <div id="chart_providers" class="time-chart" style="height: 400px;" charts="counter_fb_updated,counter_google_updated"></div>
            
        </div>
    </div>
    <div class="clear"></div>
</div>

<script type="text/javascript">
var chart;
/*
var chartData = [{
    year: 2005,
    income: 23.5,
    expenses: 18.1},
{
    year: 2006,
    income: 26.2,
    expenses: 22.8},
{
    year: 2007,
    income: 30.1,
    expenses: 23.9},
{
    year: 2008,
    income: 29.5,
    expenses: 25.1},
{
    year: 2009,
    income: 24.6,
    expenses: 25.0}];
*/

window['chartData'] = $.parseJSON('<?php echo $users_stats; ?>');

$(".time-chart").mouseover(function(){
    var id = $(this).attr('id');
    window['current_graph'] = id;
});

$(".time-chart").mouseout(function(){
    window['current_graph'] = '';
});

$( ".time-chart" ).each(function( index ) {
    var id = $(this).attr('id');
    var mainchart = $(this).attr('mainchart');
    var charts = $(this).attr('charts');
    var charts_array = charts.split(',');
    
    window['chart' + id] = new AmCharts.AmSerialChart();
    
    window['chart' + id].pathToImages = "http://www.amcharts.com/lib/images/";
    window['chart' + id].autoMarginOffset = 0;
    window['chart' + id].marginRight = 0;
    window['chart' + id].dataProvider = window['chartData'];
    window['chart' + id].categoryField = "date";
    window['chart' + id].startDuration = 1;
    window['chart' + id].balloon.bulletSize = 5;
    
    window['chart' + id].zoomOutButton = {
        backgroundColor: '#000000',
        backgroundAlpha: 0.15
    };
    
    window['chart' + id].categoryAxis.gridPosition = "start";
    
    window['chartCursor' + id] = new AmCharts.ChartCursor();
    window['chartCursor' + id].cursorPosition = "middle"; // start, mouse
    window['chartCursor' + id].pan = true; // set it to fals if you want the cursor to work in "select" mode
    window['chart' + id].addChartCursor(window['chartCursor' + id]);
                   
    window['legend' + id] = new AmCharts.AmLegend();
    window['chart' + id].addLegend(window['legend' + id]);
    
    // GENERATE CHARTS
    // registered / activated graph
    window['counter_activated'] = new AmCharts.AmGraph();
    window['counter_activated'].type = "column";
    window['counter_activated'].lineColor = "#5475d3";
    window['counter_activated'].title = "Activated";
    window['counter_activated'].valueField = "counter_activated";
    window['counter_activated'].lineAlpha = 0;
    window['counter_activated'].fillAlphas = 0.85;
    
    window['counter_registered'] = new AmCharts.AmGraph();
    window['counter_registered'].type = "line";
    window['counter_registered'].title = "Registered";
    window['counter_registered'].valueField = "counter_registered";
    window['counter_registered'].lineThickness = 2;
    window['counter_registered'].bullet = "round";
    
    // registration types
    window['counter_classic_registered'] = new AmCharts.AmGraph();
    window['counter_classic_registered'].type = "column";
    window['counter_classic_registered'].lineColor = "yellow";
    window['counter_classic_registered'].title = "Form registration";
    window['counter_classic_registered'].valueField = "counter_classic_registered";
    window['counter_classic_registered'].lineAlpha = 0;
    window['counter_classic_registered'].fillAlphas = 0.85;
    
    window['counter_fb_registered'] = new AmCharts.AmGraph();
    window['counter_fb_registered'].type = "column";
    window['counter_fb_registered'].lineColor = "#5475d3";
    window['counter_fb_registered'].title = "Facebook registration";
    window['counter_fb_registered'].valueField = "counter_fb_registered";
    window['counter_fb_registered'].lineAlpha = 0;
    window['counter_fb_registered'].fillAlphas = 0.85;
    
    window['counter_google_registered'] = new AmCharts.AmGraph();
    window['counter_google_registered'].type = "column";
    window['counter_google_registered'].lineColor = "red";
    window['counter_google_registered'].title = "Google registration";
    window['counter_google_registered'].valueField = "counter_google_registered";
    window['counter_google_registered'].lineAlpha = 0;
    window['counter_google_registered'].fillAlphas = 0.85;
    
    // updated by social
    window['counter_fb_updated'] = new AmCharts.AmGraph();
    window['counter_fb_updated'].type = "column";
    window['counter_fb_updated'].lineColor = "#5475d3";
    window['counter_fb_updated'].title = "Facebook";
    window['counter_fb_updated'].valueField = "counter_fb_updated";
    window['counter_fb_updated'].lineAlpha = 0;
    window['counter_fb_updated'].fillAlphas = 0.85;
    
    window['counter_google_updated'] = new AmCharts.AmGraph();
    window['counter_google_updated'].type = "column";
    window['counter_google_updated'].lineColor = "red";
    window['counter_google_updated'].title = "Google";
    window['counter_google_updated'].valueField = "counter_google_updated";
    window['counter_google_updated'].lineAlpha = 0;
    window['counter_google_updated'].fillAlphas = 0.85;
    
    window['chartScrollbar' + id] = new AmCharts.ChartScrollbar();
    if (window[mainchart]){
        window['chartScrollbar' + id].graph = window[mainchart];
    }
    window['chart' + id].addChartScrollbar(window['chartScrollbar' + id]);
    
    $.each(charts_array, function(i, item) {
        window['chart' + id].addGraph(window[charts_array[i]]);
    });
    //window['chart' + id].addGraph(graph_chart_counter_confirmed_payment_amount);
    //window['chart' + id].addGraph(graph_chart_counter_pending_payment_amount);
});

$( ".time-chart" ).each(function( index ) {
    var id = $(this).attr('id');
    window['chart' + id].addListener("dataUpdated", zoomChart);
    window['chart' + id].addListener("zoomed", zoomOther);
    window['chart' + id].write(id);
});

function arrange_tabs(){
    $( ".chart" ).each(function( index ) {
        if ( $(this).hasClass("out") ) {
            $(this).removeClass('out').removeClass('active');
        }
    });
}

function zoomChart() {
    $( ".time-chart" ).each(function( index ) {
        var id = $(this).attr('id');
        window['chart' + id].zoomToIndexes(chartData.length - 7, chartData.length - 1);
    });
}

function zoomOther() {
    var current_id = window['current_graph'];
    if (current_id){
        $( ".time-chart" ).each(function( index ) {
            var id = $(this).attr('id');
            if (id != window['current_graph']){
                window['chart' + id].zoomToIndexes(window['chart' + current_id].startIndex, window['chart' + current_id].endIndex);
            }
        });
    }
}

function change_period(){
    var period = $("#select_period").val();
    $("#redirect_stats").attr('href', '/admin/interface/users/users_stats/' + period + '/');
    $("#redirect_stats").trigger("click");
}

setTimeout(function(){arrange_tabs()},1000)
</script>