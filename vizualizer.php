<!DOCTYPE html>

<html lang="en"> 

<head> 

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>


  <!-- <meta charset="UTF-8">
  <meta name= "viewport" content="width=device-width"> -->

</head>

<body >

  <!-- Header and buttons -->
  <div class="container">
   
    <!-- header row --> 
    <!-- <div class="row">
      <div class="col-sm-2">
        <button 
          style="
            font-family: Helvetica;
            font-size: larger;
            float: left;
            margin: 6rem 0rem 0rem 3rem;">
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

    </div> -->



    <!-- Dashboard -->
        <div id="dashboard_div" class="collapse in" >
        
          <div id="dashChart_div"></div>
  
          <div id="filter_div"></div>
  
        <!-- </div> -->
      

    <!-- second row of buttons (selection, submit and reset buttons) -->
    <div class="row">
      <form method="post" id="framework_form">
        <div class="form-group">
          <div class="col-sm-3" >
            <select 
              class="form-control"
              name="framework[]"
              id="framework" 
              multiple
            >

            <!-- <option id = "venOptions"></option>
            <script>

              $( "#venOptions" ).load( "vendorsData.php" );
            </script> -->

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

                foreach($dataVendors as $row)
                {
                  echo '<option value="'.$row.'">'.$row.'</option>';
                }
              ?>

            </select>
          </div>

          <div class="col-sm-3" >
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
              value="Update"
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

          <div class="col-sm-2">
            <button class="viz-btn" data-toggle="collapse" data-target="#chart_div" aria-expanded="false" aria-controls="chart_div">Bubble Viz</button>
          </div>
          
        </div>
      </form>
    </div>

  </div>

    <br/>

  </div>

  <!-- <br/>
  <br/> -->

  <!-- Chart canvas -->
  <div id="chart_div" class="collapse">

    <div class="chartClass" id="chart" >
  
      <div class="row ">
  
        <div class="col-sm-1" style="margin-top: 1rem;">
          <label class="switch" style="float:left;">
            <input type="checkbox" onchange="toggleFunction()">
            <span class="slider round"></span>
          </label>
        </div>
        
        <div class="col-sm-1" style="margin-top: 1rem;" >
          <button
            class="btn btn-style btn-sm btn-block responsive-width btn-width"
            id="seperate"
            name="separate"
            
          >
            Separate
          </button>
        </div>
      
        <div class="col-sm-1" style="margin-top: 1rem;" >
          <button
            
            class="btn btn-style btn-sm btn-block responsive-width btn-width"
            id="frozen"
            name="frozen"
            
          >
            Frozen
          </button>
        </div>
  
        <div class="col-sm-1" style="margin-top: 1rem;" >
          <button
            
            class="btn btn-style btn-sm btn-block responsive-width btn-width"
            id="Out-of-Stock"
            name="Out-of-Stock"
            
          >
            Out-of-Stock
          </button>
        </div>
      
        <div class="col-sm-1" style="margin-top: 1rem;" >
          <button
            
            class="btn btn-style btn-sm btn-block responsive-width btn-width"
            id="orderSmape"
            name="orderSmape"
            
          >
            Order Error
          </button>
        </div>
      
        <div class="col-sm-1" style="margin-top: 1rem;" >
          <button
            
            class="btn btn-style btn-sm btn-block responsive-width btn-width"
            id="smapeHeatmap"
            name="smapeHeatmap"
            
          >
            Error Heatmap
          </button>
        </div>
  
        <div class="col-sm-1" style="margin-top: 1rem;" >
          <button
            
            class="btn btn-style btn-sm btn-block responsive-width btn-width"
            id="collapse"
            name="collapse"
            
          >
            Collapse
          </button>
        </div>

        <div class="col-sm-1" style="margin-top: 1.3rem;">
          <button 
            class="viz-btn" 
            data-toggle="collapse" 
            data-target="#dashboard_div" 
            aria-expanded="false" 
            aria-controls="dashboard_div"
          >
              Dashboard
            </button>
        </div>
  
        <div class="col-sm-4" style="margin-top: 1rem;" >
          <div style="float: right;">
            <button
  
            class="btn btn-style btn-sm btn-block responsive-width btn-width"
            id="Data"
            name="Data"
          >
            Data
          </button>
          </div>
          
        </div>
  
        <div class="uploadFileButton">
          <input 
            class="uploadFile btn " 
            id = "file" 
            type="hidden"
          />
          
        </div>
      
      </div>
  
      <svg></svg>
  
    </div>
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

    .btn-width {
      width: 10rem !important;
    }
    
    .viz-btn {
      font-family: Helvetica;
      text-align: center;
      background-color: #d4d1d1;
      border-color: white;
      color: #727272;
      border-radius: 1rem !important;
    }

    .btn-style {
      font-family: Helvetica;
      text-align: center;
      background-color: #8a8a8a;
      border-color: white;
      color: white;
      border-radius: 2rem !important;
    }

    .responvie-width {
      width: 11rem;
    }

    .uploadFileButton {
      overflow: hidden;
    }

    .uploadFileButton input.uploadFile {
      font-size: 12px;
    }

    .chartClass {
      background-color: #111111;
      height: 100vmax;
      /* position:absolute;
      top:0px;
      right:0px;
      bottom:0px;
      left:0px; */
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

    .multiselect-container > .active > a, 
    .multiselect-container > .active > a:hover {
      background-color: #8a8a8a !important;
    }
    .multiselect-container > .active > a:focus {
      background-color: #8a8a8a !important;
    }

    
    .ui-datepicker {
      width: 25% !important;
    }

    .ui-datepicker-header {
    background-color: #8a8a8a !important;
    }

    .ui-datepicker-title {
    color: #ffffff;
    font-size: small;
    font-family: Helvetica !important;
    }

    .ui-datepicker td {
    padding: 0;
    font-size: small;
    background-color: #8b8b8b32 !important;
    }

    .ui-datepicker-calendar {
      background: #ffffff;
    }

    .ui-widget-content .ui-state-default {
      font-family: Helvetica !important;
      border: 0px;
      text-align: center;
      background: #ffffff !important;
      font-weight: normal;
      color: #444444 !important;
    }

    .ui-widget-content .ui-state-default:hover {
      font-family: Helvetica !important;
      border: 0px;
      text-align: center;
      background: #8a8a8a !important;
      font-weight: bolder;
      color: #ffffff !important;
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
      includeSelectAllOption: true,
      buttonWidth: '100%',
      buttonClass: 'btn btn-style btn-sm btn-block responsive-width'
    })
    .multiselect('selectAll', true)
    .multiselect('updateButtonText');

    $('#framework_form').on('submit', function(event){
      google.charts.load('current', {'packages':['corechart', 'controls']});
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

          if (data.startsWith("Select Date")){
            alert(data);
          }
          else if(data.startsWith("Please select")){
            alert(data);
          }
          else{
            data = JSON.parse(data);

            var chart = bubbleChart().width(window.innerWidth).height(window.innerHeight);
            d3.select('body').select('#chart').datum(data).call(chart);
          }

        }
      });

      $.ajax({
        url:"/sqlDashboardData.php",
        method:"POST",
        data: form_data,
        dataType: "json",
        success:function(data){

          google.charts.setOnLoadCallback(drawDashboard(data));
          
        }
      });
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
<script src="vendorsData.php"></script>
<!-- <script src="fill.js"></script> -->

</html>