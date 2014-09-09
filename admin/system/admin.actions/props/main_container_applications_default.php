<?php

/**
 * @author Danijel
 * @copyright 2013
 */

global $set_period, $payment_stats, $today_count, $today_amount, $week_count, $week_amount, $month_count, $month_amount;

?>
<div class="custom-container title-container">
    <h3>Applications summary</h3>
    <p>Overview and statistics of applications.</p>
</div>

<div class="custom-container">
    <div>
        <div style="float: left;">
            <h4>Subscription statistics</h4>
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
            <p>Confirmed: <strong><?php echo $month_count; ?></strong></p>
            <p>Amount: <strong><?php echo $month_amount; ?> €</strong></p>
        </div>
        <div style="float: right;" class="well stats-well">
            <h5>THIS WEEK:</h5>
            <p>Confirmed: <strong><?php echo $week_count; ?></strong></p>
            <p>Amount: <strong><?php echo $week_amount; ?> €</strong></p>
        </div>
        <div style="float: right;" class="well stats-well">
            <h5>TODAY:</h5>
            <p>Confirmed: <strong><?php echo $today_count; ?></strong></p>
            <p>Amount: <strong><?php echo $today_amount; ?> €</strong></p>
        </div>
        <div class="clear"></div>
    </div>
    <ul id="charts-tabs" class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" no-follow="true" href="#amount_chart" style="text-align: center;">Purchases<br />(amount in €)</a></li>
        <li><a data-toggle="tab" no-follow="true" href="#number_chart" style="text-align: center;">Purchases<br />(number)</a></li>
        <li><a data-toggle="tab" no-follow="true" href="#providers_amount_chart" style="text-align: center;">Purchases by providers<br />(amount in €)</a></li>
        <li><a data-toggle="tab" no-follow="true" href="#providers_number_chart" style="text-align: center;">Purchases by providers<br />(number)</a></li>
        <li><a data-toggle="tab" no-follow="true" href="#packages_amount_chart" style="text-align: center;">Purchases by packages<br />(amount in €)</a></li>
        <li><a data-toggle="tab" no-follow="true" href="#packages_number_chart" style="text-align: center;">Purchases by packages<br />(number)</a></li>
    </ul>
    <div id="charts-tabsContent" class="tab-content charts-container">
        <div id="amount_chart" class="chart tab-pane fade active in">
            
                <div class="cover"></div>
                <div id="chart" class="time-chart" style="height: 400px;" mainchart="counter_confirmed_payment_amount" charts="counter_confirmed_payment_amount,counter_pending_payment_amount"></div>
            
        </div>
        <div id="number_chart" class="chart tab-pane fade active out">
            
                <div class="cover"></div>
                <div id="chart_numbers" class="time-chart" style="height: 400px;" mainchart="counter_confirmed_payment" charts="counter_confirmed_payment,counter_pending_payment"></div>
            
        </div>
        <div id="providers_amount_chart" class="chart tab-pane fade active out">
            
                <div class="cover"></div>
                <div id="chart_providers" class="time-chart" style="height: 400px;" charts="counter_confirmed_payment_g2s_amount,counter_confirmed_payment_moneta_amount,counter_confirmed_payment_paymentorder_amount,counter_pending_payment_g2s_amount,counter_pending_payment_moneta_amount,counter_pending_payment_paymentorder_amount"></div>
            
        </div>
        <div id="providers_number_chart" class="chart tab-pane fade active out">
            
                <div class="cover"></div>
                <div id="chart_providers_number" class="time-chart" style="height: 400px;" charts="counter_confirmed_payment_g2s,counter_confirmed_payment_moneta,counter_confirmed_payment_paymentorder,counter_pending_payment_g2s,counter_pending_payment_moneta,counter_pending_payment_paymentorder"></div>
            
        </div>
        <div id="packages_amount_chart" class="chart tab-pane fade active out">
            
                <div class="cover"></div>
                <div id="chart_packages_amount" class="time-chart" style="height: 400px;" charts="counter_confirmed_payment_3_months_amount,counter_confirmed_payment_6_months_amount,counter_confirmed_payment_12_months_amount,counter_pending_payment_3_months_amount,counter_pending_payment_6_months_amount,counter_pending_payment_12_months_amount"></div>
            
        </div>
        <div id="packages_number_chart" class="chart tab-pane fade active out">
            
                <div class="cover"></div>
                <div id="chart_packages_number" class="time-chart" style="height: 400px;" charts="counter_confirmed_payment_3_months,counter_confirmed_payment_6_months,counter_confirmed_payment_12_months,counter_pending_payment_3_months,counter_pending_payment_6_months,counter_pending_payment_12_months"></div>
            
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

window['chartData'] = $.parseJSON('<?php echo $payment_stats; ?>');

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
    // payment amount graph
    window['counter_confirmed_payment_amount'] = new AmCharts.AmGraph();
    window['counter_confirmed_payment_amount'].type = "column";
    window['counter_confirmed_payment_amount'].lineColor = "#5475d3";
    window['counter_confirmed_payment_amount'].title = "Confirmed payments";
    window['counter_confirmed_payment_amount'].valueField = "counter_confirmed_payment_amount";
    window['counter_confirmed_payment_amount'].lineAlpha = 0;
    window['counter_confirmed_payment_amount'].fillAlphas = 0.85;
    
    window['counter_pending_payment_amount'] = new AmCharts.AmGraph();
    window['counter_pending_payment_amount'].type = "line";
    window['counter_pending_payment_amount'].title = "Pending / cancelled payments";
    window['counter_pending_payment_amount'].valueField = "counter_pending_payment_amount";
    window['counter_pending_payment_amount'].lineThickness = 2;
    window['counter_pending_payment_amount'].bullet = "round";
    
    // number of payments graph
    window['counter_confirmed_payment'] = new AmCharts.AmGraph();
    window['counter_confirmed_payment'].type = "column";
    window['counter_confirmed_payment'].lineColor = "#5475d3";
    window['counter_confirmed_payment'].title = "Confirmed payments";
    window['counter_confirmed_payment'].valueField = "counter_confirmed_payment";
    window['counter_confirmed_payment'].lineAlpha = 0;
    window['counter_confirmed_payment'].fillAlphas = 0.85;
    
    window['counter_pending_payment'] = new AmCharts.AmGraph();
    window['counter_pending_payment'].type = "line";
    window['counter_pending_payment'].title = "Pending / cancelled payments";
    window['counter_pending_payment'].valueField = "counter_pending_payment";
    window['counter_pending_payment'].lineThickness = 2;
    window['counter_pending_payment'].bullet = "round";
    
    // payment providers by amount
    window['counter_confirmed_payment_g2s_amount'] = new AmCharts.AmGraph();
    window['counter_confirmed_payment_g2s_amount'].type = "column";
    window['counter_confirmed_payment_g2s_amount'].lineColor = "#5475d3";
    window['counter_confirmed_payment_g2s_amount'].title = "G2S (confirmed)";
    window['counter_confirmed_payment_g2s_amount'].valueField = "counter_confirmed_payment_g2s_amount";
    window['counter_confirmed_payment_g2s_amount'].lineAlpha = 0;
    window['counter_confirmed_payment_g2s_amount'].fillAlphas = 0.85;
    
    window['counter_pending_payment_g2s_amount'] = new AmCharts.AmGraph();
    window['counter_pending_payment_g2s_amount'].type = "line";
    window['counter_pending_payment_g2s_amount'].lineColor = "#5475d3";
    window['counter_pending_payment_g2s_amount'].title = "G2S (pending/cancelled)";
    window['counter_pending_payment_g2s_amount'].valueField = "counter_pending_payment_g2s_amount";
    window['counter_pending_payment_g2s_amount'].lineThickness = 2;
    window['counter_pending_payment_g2s_amount'].bullet = "round";
    
    window['counter_confirmed_payment_moneta_amount'] = new AmCharts.AmGraph();
    window['counter_confirmed_payment_moneta_amount'].type = "column";
    window['counter_confirmed_payment_moneta_amount'].lineColor = "red";
    window['counter_confirmed_payment_moneta_amount'].title = "Moneta (confirmed)";
    window['counter_confirmed_payment_moneta_amount'].valueField = "counter_confirmed_payment_moneta_amount";
    window['counter_confirmed_payment_moneta_amount'].lineAlpha = 0;
    window['counter_confirmed_payment_moneta_amount'].fillAlphas = 0.85;
    
    window['counter_pending_payment_moneta_amount'] = new AmCharts.AmGraph();
    window['counter_pending_payment_moneta_amount'].type = "line";
    window['counter_pending_payment_moneta_amount'].lineColor = "red";
    window['counter_pending_payment_moneta_amount'].title = "Moneta (pending/cancelled)";
    window['counter_pending_payment_moneta_amount'].valueField = "counter_pending_payment_moneta_amount";
    window['counter_pending_payment_moneta_amount'].lineThickness = 2;
    window['counter_pending_payment_moneta_amount'].bullet = "round";
    
    window['counter_confirmed_payment_paymentorder_amount'] = new AmCharts.AmGraph();
    window['counter_confirmed_payment_paymentorder_amount'].type = "column";
    window['counter_confirmed_payment_paymentorder_amount'].lineColor = "yellow";
    window['counter_confirmed_payment_paymentorder_amount'].title = "Payment order (confirmed)";
    window['counter_confirmed_payment_paymentorder_amount'].valueField = "counter_confirmed_payment_paymentorder_amount";
    window['counter_confirmed_payment_paymentorder_amount'].lineAlpha = 0;
    window['counter_confirmed_payment_paymentorder_amount'].fillAlphas = 0.85;
    
    window['counter_pending_payment_paymentorder_amount'] = new AmCharts.AmGraph();
    window['counter_pending_payment_paymentorder_amount'].type = "line";
    window['counter_pending_payment_paymentorder_amount'].lineColor = "yellow";
    window['counter_pending_payment_paymentorder_amount'].title = "Payment order (pending)";
    window['counter_pending_payment_paymentorder_amount'].valueField = "counter_pending_payment_paymentorder_amount";
    window['counter_pending_payment_paymentorder_amount'].lineThickness = 2;
    window['counter_pending_payment_paymentorder_amount'].bullet = "round";
    
    // payment providers by number
    window['counter_confirmed_payment_g2s'] = new AmCharts.AmGraph();
    window['counter_confirmed_payment_g2s'].type = "column";
    window['counter_confirmed_payment_g2s'].lineColor = "#5475d3";
    window['counter_confirmed_payment_g2s'].title = "G2S (confirmed)";
    window['counter_confirmed_payment_g2s'].valueField = "counter_confirmed_payment_g2s";
    window['counter_confirmed_payment_g2s'].lineAlpha = 0;
    window['counter_confirmed_payment_g2s'].fillAlphas = 0.85;
    
    window['counter_pending_payment_g2s'] = new AmCharts.AmGraph();
    window['counter_pending_payment_g2s'].type = "line";
    window['counter_pending_payment_g2s'].lineColor = "#5475d3";
    window['counter_pending_payment_g2s'].title = "G2S (pending/cancelled)";
    window['counter_pending_payment_g2s'].valueField = "counter_pending_payment_g2s";
    window['counter_pending_payment_g2s'].lineThickness = 2;
    window['counter_pending_payment_g2s'].bullet = "round";
    
    window['counter_confirmed_payment_moneta'] = new AmCharts.AmGraph();
    window['counter_confirmed_payment_moneta'].type = "column";
    window['counter_confirmed_payment_moneta'].lineColor = "red";
    window['counter_confirmed_payment_moneta'].title = "Moneta (confirmed)";
    window['counter_confirmed_payment_moneta'].valueField = "counter_confirmed_payment_moneta";
    window['counter_confirmed_payment_moneta'].lineAlpha = 0;
    window['counter_confirmed_payment_moneta'].fillAlphas = 0.85;
    
    window['counter_pending_payment_moneta'] = new AmCharts.AmGraph();
    window['counter_pending_payment_moneta'].type = "line";
    window['counter_pending_payment_moneta'].lineColor = "red";
    window['counter_pending_payment_moneta'].title = "Moneta (pending/cancelled)";
    window['counter_pending_payment_moneta'].valueField = "counter_pending_payment_moneta";
    window['counter_pending_payment_moneta'].lineThickness = 2;
    window['counter_pending_payment_moneta'].bullet = "round";
    
    window['counter_confirmed_payment_paymentorder'] = new AmCharts.AmGraph();
    window['counter_confirmed_payment_paymentorder'].type = "column";
    window['counter_confirmed_payment_paymentorder'].lineColor = "yellow";
    window['counter_confirmed_payment_paymentorder'].title = "Payment order (confirmed)";
    window['counter_confirmed_payment_paymentorder'].valueField = "counter_confirmed_payment_paymentorder";
    window['counter_confirmed_payment_paymentorder'].lineAlpha = 0;
    window['counter_confirmed_payment_paymentorder'].fillAlphas = 0.85;
    
    window['counter_pending_payment_paymentorder'] = new AmCharts.AmGraph();
    window['counter_pending_payment_paymentorder'].type = "line";
    window['counter_pending_payment_paymentorder'].lineColor = "yellow";
    window['counter_pending_payment_paymentorder'].title = "Payment order (pending)";
    window['counter_pending_payment_paymentorder'].valueField = "counter_pending_payment_paymentorder";
    window['counter_pending_payment_paymentorder'].lineThickness = 2;
    window['counter_pending_payment_paymentorder'].bullet = "round";
    
    // packages by amount
    window['counter_confirmed_payment_3_months_amount'] = new AmCharts.AmGraph();
    window['counter_confirmed_payment_3_months_amount'].type = "column";
    window['counter_confirmed_payment_3_months_amount'].lineColor = "#5475d3";
    window['counter_confirmed_payment_3_months_amount'].title = "3 months (confirmed)";
    window['counter_confirmed_payment_3_months_amount'].valueField = "counter_confirmed_payment_3_months_amount";
    window['counter_confirmed_payment_3_months_amount'].lineAlpha = 0;
    window['counter_confirmed_payment_3_months_amount'].fillAlphas = 0.85;
    
    window['counter_pending_payment_3_months_amount'] = new AmCharts.AmGraph();
    window['counter_pending_payment_3_months_amount'].type = "line";
    window['counter_pending_payment_3_months_amount'].lineColor = "#5475d3";
    window['counter_pending_payment_3_months_amount'].title = "3 months (pending/cancelled)";
    window['counter_pending_payment_3_months_amount'].valueField = "counter_pending_payment_3_months_amount";
    window['counter_pending_payment_3_months_amount'].lineThickness = 2;
    window['counter_pending_payment_3_months_amount'].bullet = "round";
    
    window['counter_confirmed_payment_6_months_amount'] = new AmCharts.AmGraph();
    window['counter_confirmed_payment_6_months_amount'].type = "column";
    window['counter_confirmed_payment_6_months_amount'].lineColor = "red";
    window['counter_confirmed_payment_6_months_amount'].title = "6 months (confirmed)";
    window['counter_confirmed_payment_6_months_amount'].valueField = "counter_confirmed_payment_6_months_amount";
    window['counter_confirmed_payment_6_months_amount'].lineAlpha = 0;
    window['counter_confirmed_payment_6_months_amount'].fillAlphas = 0.85;
    
    window['counter_pending_payment_6_months_amount'] = new AmCharts.AmGraph();
    window['counter_pending_payment_6_months_amount'].type = "line";
    window['counter_pending_payment_6_months_amount'].lineColor = "red";
    window['counter_pending_payment_6_months_amount'].title = "6 months (pending/cancelled)";
    window['counter_pending_payment_6_months_amount'].valueField = "counter_pending_payment_6_months_amount";
    window['counter_pending_payment_6_months_amount'].lineThickness = 2;
    window['counter_pending_payment_6_months_amount'].bullet = "round";
    
    window['counter_confirmed_payment_12_months_amount'] = new AmCharts.AmGraph();
    window['counter_confirmed_payment_12_months_amount'].type = "column";
    window['counter_confirmed_payment_12_months_amount'].lineColor = "yellow";
    window['counter_confirmed_payment_12_months_amount'].title = "12 months (confirmed)";
    window['counter_confirmed_payment_12_months_amount'].valueField = "counter_confirmed_payment_12_months_amount";
    window['counter_confirmed_payment_12_months_amount'].lineAlpha = 0;
    window['counter_confirmed_payment_12_months_amount'].fillAlphas = 0.85;
    
    window['counter_pending_payment_12_months_amount'] = new AmCharts.AmGraph();
    window['counter_pending_payment_12_months_amount'].type = "line";
    window['counter_pending_payment_12_months_amount'].lineColor = "yellow";
    window['counter_pending_payment_12_months_amount'].title = "12 months (pending)";
    window['counter_pending_payment_12_months_amount'].valueField = "counter_pending_payment_12_months_amount";
    window['counter_pending_payment_12_months_amount'].lineThickness = 2;
    window['counter_pending_payment_12_months_amount'].bullet = "round";
    
    // packages by number
    window['counter_confirmed_payment_3_months'] = new AmCharts.AmGraph();
    window['counter_confirmed_payment_3_months'].type = "column";
    window['counter_confirmed_payment_3_months'].lineColor = "#5475d3";
    window['counter_confirmed_payment_3_months'].title = "3 months (confirmed)";
    window['counter_confirmed_payment_3_months'].valueField = "counter_confirmed_payment_3_months";
    window['counter_confirmed_payment_3_months'].lineAlpha = 0;
    window['counter_confirmed_payment_3_months'].fillAlphas = 0.85;
    
    window['counter_pending_payment_3_months'] = new AmCharts.AmGraph();
    window['counter_pending_payment_3_months'].type = "line";
    window['counter_pending_payment_3_months'].lineColor = "#5475d3";
    window['counter_pending_payment_3_months'].title = "3 months (pending/cancelled)";
    window['counter_pending_payment_3_months'].valueField = "counter_pending_payment_3_months";
    window['counter_pending_payment_3_months'].lineThickness = 2;
    window['counter_pending_payment_3_months'].bullet = "round";
    
    window['counter_confirmed_payment_6_months'] = new AmCharts.AmGraph();
    window['counter_confirmed_payment_6_months'].type = "column";
    window['counter_confirmed_payment_6_months'].lineColor = "red";
    window['counter_confirmed_payment_6_months'].title = "6 months (confirmed)";
    window['counter_confirmed_payment_6_months'].valueField = "counter_confirmed_payment_6_months";
    window['counter_confirmed_payment_6_months'].lineAlpha = 0;
    window['counter_confirmed_payment_6_months'].fillAlphas = 0.85;
    
    window['counter_pending_payment_6_months'] = new AmCharts.AmGraph();
    window['counter_pending_payment_6_months'].type = "line";
    window['counter_pending_payment_6_months'].lineColor = "red";
    window['counter_pending_payment_6_months'].title = "6 months (pending/cancelled)";
    window['counter_pending_payment_6_months'].valueField = "counter_pending_payment_6_months";
    window['counter_pending_payment_6_months'].lineThickness = 2;
    window['counter_pending_payment_6_months'].bullet = "round";
    
    window['counter_confirmed_payment_12_months'] = new AmCharts.AmGraph();
    window['counter_confirmed_payment_12_months'].type = "column";
    window['counter_confirmed_payment_12_months'].lineColor = "yellow";
    window['counter_confirmed_payment_12_months'].title = "12 months (confirmed)";
    window['counter_confirmed_payment_12_months'].valueField = "counter_confirmed_payment_12_months";
    window['counter_confirmed_payment_12_months'].lineAlpha = 0;
    window['counter_confirmed_payment_12_months'].fillAlphas = 0.85;
    
    window['counter_pending_payment_12_months'] = new AmCharts.AmGraph();
    window['counter_pending_payment_12_months'].type = "line";
    window['counter_pending_payment_12_months'].lineColor = "yellow";
    window['counter_pending_payment_12_months'].title = "12 months (pending)";
    window['counter_pending_payment_12_months'].valueField = "counter_pending_payment_12_months";
    window['counter_pending_payment_12_months'].lineThickness = 2;
    window['counter_pending_payment_12_months'].bullet = "round";
    
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
    $("#redirect_stats").attr('href', '/admin/interface/applications/applications_default/' + period + '/');
    $("#redirect_stats").trigger("click");
}

setTimeout(function(){arrange_tabs()},1000)
</script>