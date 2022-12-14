<!Doctype Html>
<html lang="en"> 

  <head> 
    <title>AU RFID Bubble Viz</title>

      
      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      <link rel="shortcut icon" href="#">
      <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
      
      <link rel="stylesheet" href="sidebarStyling.css">
      <link rel="stylesheet" href="style.css">
      

  </head>

  <body>

    <!-- Header -->
    <div class="row" id="myHeader" style="background-color: #ffffffe1; width: 100vmax !important; margin:0rem">
        <!-- <div class="col" style="float: left; margin-left: 0;">
            <button class="openbtn" onclick="openNav()">☰</button>
        </div>

        <div class="col" >
      
        </div>

        <div class="col">
            <a href="https://rfid.auburn.edu/" target="_blank">
                <img
                src="https://www.logolynx.com/images/logolynx/7d/7d83f243c2e27ebe79fd4f4accf4c3f4.png"
                alt="AU RFID Lab"
                style="
                    float: right;
                    image-resolution: from-image;
                    width: 5rem;
                    height: auto "/>
                </a>
        </div>   -->
    </div>  

    <div id="mySidepanel" class="sidepanel">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <a href="/#home">Home</a>
        <a href="/#how">How It Works</a>
        <a href="/#exact">Exact Match</a>
        <a href="/#over">Overstated</a>
        <a href="/#frozen">Frozen</a>
        <a href="/#under">Understated</a>
        <a href="/#upload">Upload</a>
        <a href="/vizualizer.php">DB Server</a>
        <a href="/vizualizerFile.html">File Upload</a>
    </div>
    
    <!-- Buttons -->
    <div class="container">
      <!-- Dashboard -->
      <div id="dashboard_div" class="collapse in" >
      
        <div id="dashChart_div"></div>

        <div id="filter_div"></div>

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

                    $mysqli = require __DIR__ . "/db.php";
                    // if( $conn === false ) {
                    //   die(print_r( sqlsrv_errors(), true));
                    // }

                    // get vendors
                    $getVendorsquery = "
                      SELECT DISTINCT(Vendor_Name)
                      FROM wal_main_apr6tojul02_filtered_prices
                      ";
                    $vendors = $mysqli->query($getVendorsquery);
                    $dataVendors = array();
                    while ($row = $vendors->fetch_array(MYSQLI_ASSOC)) {
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
        <br/>
      </div>

    </div>

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
                id="InStore"
                name="InStore"
                
              >

                Instore View
              </button>
          </div>

          <div class="col-sm-1" style="margin-top: 1rem;" >
            <button
              
              class="btn btn-style btn-sm btn-block responsive-width btn-width"
              id="Online"
              name="Online"
              
              >

              Online View
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
    
          <div class="col-sm-2" style="margin-top: 1rem;" >
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

  </body>

  <script>
    // toggle canvas background
    function toggleFunction() {
      document.getElementById('chart').classList.toggle("toggleBackground");
    }

    // sidepanel
    function openNav() {
      document.getElementById("mySidepanel").style.width = "210px";
    }
    
    function closeNav() {
      document.getElementById("mySidepanel").style.width = "0";
    }

    window.onscroll = function() {myFunction()};

    var header = document.getElementById("myHeader");
    var sticky = header.offsetTop;

    function myFunction() {
      if (window.pageYOffset > sticky) {
        header.classList.remove("sticky");
      } 
      else {
        header.classList.remove("sticky");
      }
    }

    // selection of vendor and date
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
              // alert(data);
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
            // alert(data);
            google.charts.setOnLoadCallback(drawDashboard(data));
          }
        });

      });
    });

  </script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/4.6.0/d3.min.js"></script>
  <script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="calculations.js"></script>
  <script src="bubbleChart.js"></script>
  <script src="sqlDashboardData.php"></script>
  <script src="dashboard.js"></script>
  <script src="vendorsData.php"></script>

</html>

