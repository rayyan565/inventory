google.charts.load('current', {'packages':['corechart', 'controls']});
google.charts.setOnLoadCallback(drawDashboard);

function drawDashboard(){

var jsonData = $.ajax({
    url: "sqlDashboardData.php",
    dataType: "json",
    async: false
    }).responseText;
    
var data = new google.visualization.DataTable(jsonData);


// var data = new google.visualization.DataTable(<?php echo $jsonTable; ?>);

var dashboard = new google.visualization.Dashboard(document.getElementById('dashboard_div'));

var dateRangeSlider = new google.visualization.ControlWrapper({
    'controlType': 'DateRangeFilter',
    'containerId': 'filter_div',
    'options': {
    title:'',
    filterColumnIndex: 0,
    ui: {
        chartOptions: {
        chartArea: {
            width:'100%', 
            height: 'auto',
        },
        
        }
    },
    hAxis: {
        baselineColor: 'none'
    },
    vAxis: {
        textStyle: {
        fontSize: 10,
        bold: false
        },
        titleTextStyle:{
        italic: false
        }
    }   
    }
});

var chart = new 
google.visualization.ChartWrapper({
    'chartType': 'ColumnChart',    // stacked bar chart
    // 'chartType': 'LineChart',   // Line Chart 
    'containerId': 'line_div',
    'options': {
    title:'',
    legend:{position:'top'},
    isStacked: true,             // stacked bar chart
    hAxis: {
        title: '',
        textStyle: {
        fontSize: 10,
        bold: false
        },
        titleTextStyle:{
        italic: false
        }
    },
    vAxis: {
        title: '',
        textStyle: {
        fontSize: 10,
        bold: false
        },
        titleTextStyle:{
        italic: false
        }
    },
    colors: ['#00FF7F', '#FF3B28', '#00A7FA'],
    fontSize: 10,
    textStyle: "Helvetica"
    }
});

dashboard.bind(dateRangeSlider, chart);
dashboard.draw(data);

}

google.charts.setOnLoadCallback(drawDashboard);