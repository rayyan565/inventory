<!DOCTYPE html>

<?php
$serverName = "oitss5\mssql05,1433"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RFID_Inventory", "UID"=>"RFID_Inventoryuser", "PWD"=>"pO49nY1xdM");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

// get vendors
$getVendorsquery = "
  SELECT DISTINCT(Vendor_Name)
  FROM wal_main_apr6tojul02_filtered
  ";
$vendors = sqlsrv_query($conn, $getVendorsquery);
$dataVendors = array();
while ($row = sqlsrv_fetch_array($vendors, SQLSRV_FETCH_ASSOC)) {
  array_push($dataVendors, $row['Vendor_Name']);
}

?>

<html lang="en"> 

<head> 

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>


<!-- <meta charset="UTF-8">
<meta name= "viewport" content="width=device-width"> -->

</head>

<body style="background-color: white;">

  <!-- Header and buttons -->
  <div class="container">

    <!-- header row -->
    <div class="row">
      <div class="col-sm-2">
        <button 
          
          style="
            font-family: Helvetica;
            font-size: larger;
            float: left;
            margin: 6rem 0rem 0rem 3rem;"
        >
          Menu
        </button>
      </div>
      
      <div style="margin-top: 4rem;" class="col-sm-8">
        <h1 style="
              font-family: HelveticaNeue-CondensedBold; 
              text-align: center; 
              font-size: 4rem;
              color: #090b5e;">
          Auburn RFID Lab Inventory Visualizer
        </h1>
      </div>

      <div class="col-sm-2">
        <a href="https://rfid.auburn.edu/" target="_blank">
          <img
          src="https://media-exp1.licdn.com/dms/image/C510BAQGBgBMoZiE56w/company-logo_200_200/0/1519901678068?e=2159024400&v=beta&t=jDeDJ8NzJ0SwxrEn3Q5-dYplorJDDdTarbgEDtAgq0g"
          alt="AU RFID Lab"
          style="
            float: right;
            margin: 3rem 3rem 0rem 0rem;
            image-resolution: from-image;
            width: 10rem;
            height: auto;"/>
        </a>
      </div>

    </div>

    <br/>

    <!-- Dashboard -->
    <div id="dashboard_div">
      

      <!-- <div class="col-sm-8" style="float:right"> -->
        <div id="line_div" style="width: 100%; height: 75%"></div>
      <!-- </div> -->

      <div class="col-sm-1"></div>

      <div class="col-sm-4" style="float:left" >
      <div id="filter_div" style="width: 100%; height: 75%;"></div>
      </div>

      <div class="col-sm-2"></div>

      <!-- <div class="col-sm-4">
        <button
          class="btn btn-style btn-sm btn-block responsive-width"
          id="UpdateLine"
          name="UpdateLine"
          style="float: right;"
        >
          Update Line
        </button>
      </div> -->

    </div>

    <br/>
    <br/>

    <!-- second row of buttons (selection, submit and reset buttons) -->
    <div class="row">
      <form method="post" id="framework_form">
        <div class="form-group">
          <div class="col-sm-4" >
            <select 
              class="form-control"
              name="framework[]"
              id="framework" 
              multiple 
              >
              <?php
                foreach($dataVendors as $row)
                {
                  echo '<option value="'.$row.'">'.$row.'</option>';
                }
              ?>
            </select>
          </div>

          <div class="col-sm-4" >
            <input
              class="btn btn-style btn-sm btn-block responsive-width"
              name="datePicker"
              id="datepicker"
              placeholder="Select Date"
              value="Select Date">
          </div>

          <div class="col-sm-2">
            <input 
              type="submit" 
              class="btn btn-style btn-sm btn-block responsive-width" 
              name="submit" 
              value="View Charts"
              style="background-color: #8a8a8a" />
          </div>

          <div class="col-sm-2" >
            <button
              class="btn btn-style btn-sm btn-block responsive-width"
              id="resetButton"
              name="resetButton"
              style="background-color: #8a8a8a"
            >
              Reset
            </button>
          </div>

        </div>
      </form>
    </div>

    <br/>

  </div>

  <!-- Chart canvas -->
  <div class="chartClass" id="chart">
  
    <div class="row ">

      <div class="col-sm-1">
        <label class="switch" style="float:left;">
          <input type="checkbox" onchange="toggleFunction()">
          <span class="slider round"></span>
        </label>
      </div>
      
      <div class="col-sm-1" >
        <button
          
          class="responvie-width"
          id="seperate"
          name="separate"
          style="width: 11rem;"
        >
          Separate
        </button>
      </div>
    
      <div class="col-sm-1" >
        <button
          
          class="responvie-width"
          id="frozen"
          name="frozen"
          style="width: 11rem;"
        >
          Frozen
        </button>
      </div>

      <div class="col-sm-1" >
        <button
          
          class="responvie-width"
          id="Out-of-Stock"
          name="Out-of-Stock"
          style="width: 11rem;"
        >
          Out-of-Stock
        </button>
      </div>
    
      <div class="col-sm-1" >
        <button
          
          class="responvie-width"
          id="orderSmape"
          name="orderSmape"
          style="width: 11rem;"
        >
          Order Error
        </button>
      </div>
    
      <div class="col-sm-1" >
        <button
          
          class="responvie-width"
          id="smapeHeatmap"
          name="smapeHeatmap"
          style="width: 11rem;"
        >
          Error Heatmap
        </button>
      </div>

      <div class="col-sm-1" >
        <button
          
          class="responvie-width"
          id="collapse"
          name="collapse"
          style="width: 11rem;"
        >
          Collapse
        </button>
      </div>

      <div class="col-sm-5" >
        <div style="float: right;">
          <button

          class="responvie-width"
          id="Data"
          name="Data"
          style="
            width: 11rem;
            ">
          Data
        </button>
        </div>
        
      </div>

      <div class="uploadFileButton">
        <input 
          class="uploadFile btn btn-primary" 
          id = "file" 
          type="hidden"
        />
        
      </div>
    
    </div>

    <svg></svg>

  </div>

  <!-- styling -->
  <style type="text/css">

    .framework {
      font-family: Helvetica;
      text-align: center;
      background-color: #090b5e;
      border-color: white;
      color: white;
      border-radius: 1rem;
    }

    .btn-primary {
      width: 11rem;
    }

    .btn-style {
      font-family: Helvetica;
      text-align: center;
      background-color: #8a8a8a;
      border-color: white;
      color: white;
      border-radius: 1rem;
      
    }

    .uploadFileButton {
      overflow: hidden;
    }

    .uploadFileButton input.uploadFile {
      font-size: 12px;
    }

    .chartClass{
      background-color: #111111;

    }

    .toggleBackground {
      background-color: white;
    }

    .switch {
      position: relative;
      display: inline-block;
      width: 55px;
      height: 30px;
    }

    .switch input { 
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .3s;
      transition: .3s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 24px;
      width: 24px;
      left: 3px;
      bottom: 3px;
      background-color: white;
      -webkit-transition: .3s;
      transition: .3s;
    }

    input:checked + .slider {
      background-color: #525252d3;
    }

    input:focus + .slider {
      box-shadow: 0 0 1px #525252d3;
    }

    input:checked + .slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }

    .slider.round:before {
      border-radius: 50%;
    }

  </style>

</body>

<!-- toggle canvas background -->
<script>
  function toggleFunction() {
     document.getElementById('chart').classList.toggle("toggleBackground");
  }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- selection of vendor and date -->
<script>
  $(document).ready(function(){
    var finalData;
    var selectedDate;
    var test;
    $("#datepicker").datepicker({ dateFormat: "yy-mm-dd" });
    
    $('#framework').multiselect({
      nonSelectedText: 'Select Vendors',
      enableFiltering: true,
      enableCaseInsensitiveFiltering: true,
      buttonWidth: '100%',
      buttonClass: 'btn btn-style btn-sm btn-block responsive-width'
    });

    $('#framework_form').on('submit', function(event){
      d3.select("#chart").selectAll("svg > *").remove();
      event.preventDefault();
      form_data = $(this).serialize();
      selectedDate = $("#datepicker").val();
      test = 'test data';

      $.ajax({
        url:"/sqlservergetdata.php",
        method:"POST",
        data: form_data,
        success:function(data){

          data = JSON.parse(data);

          var chart = bubbleChart().width(window.innerWidth).height(window.innerHeight);
          d3.select('body').select('#chart').datum(data).call(chart);

          // alert(data);

        }
      });

      $.ajax({
        url:"/sqlDashboardData.php",
        method:"POST",
        data: form_data,
        success:function(data){
          data = JSON.parse(data);

          var data = new google.visualization.DataTable(data);

          var dashboard = new google.visualization.Dashboard(document.getElementById('dashboard_div'));

          var dateRangeSlider = new google.visualization.ControlWrapper({
              'controlType': 'DateRangeFilter',
              'containerId': 'filter_div',
              'options': {
              title:'',
              filterColumnIndex: 0,
              ui: {
                  chartOptions: {
                  chartArea: {width:'100%', height: 'auto'},
                  
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
      });

      // return false;
    });
  });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/4.6.0/d3.min.js"></script>
<!-- <script src="fileReader.js"></script> -->
<script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<script src="calculations.js"></script>
<script src="bubbleChart.js"></script>
<script src="sqlDashboardData.php"></script>
<script src="dashboard.js"></script>
<!-- <script src="fill.js"></script> -->

</html>