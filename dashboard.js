
function drawDashboard(jsonData){
    
var data = new google.visualization.DataTable(jsonData);

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
                width:'100vmax', 
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
    'containerId': 'dashChart_div',
    'options': {
        title:'',
        legend:{position:'top'},
        height:390,
        isStacked: true,             // stacked bar chart
        ui: {
            chartOptions: {
            chartArea: {
                width: '10rem', 
                height: '10rem',
            },
            
            }
        },
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

// google.charts.setOnLoadCallback(drawDashboard);