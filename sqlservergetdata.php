<?php
$serverName = "oitss5\mssql05,1433"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RFID_Inventory", "UID"=>"RFID_Inventoryuser", "PWD"=>"pO49nY1xdM");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

  
    if(isset($_POST["framework"])){
        $framework = '';
        $selectedDate = '';
        foreach($_POST["framework"] as $row){
            $framework .= $row . ',';
        }

        $selectedDate = $_REQUEST["datePicker"];

        $framework = substr($framework, 0, -1);
        $frameworkList = "'" . str_replace(",","','", $framework) . "'";

        $query = "
        SELECT SKU As SKU1, Cycle_Count_Restored As RFID, Hist_On_Hand_Qty As OH 
        FROM wal_main_apr6tojul02_filtered 
        WHERE Vendor_Name IN ($frameworkList) 
        AND Date LIKE '$selectedDate'
        ";



        // $query = "
        // SELECT SKU As SKU1, Cycle_Count_Restored As RFID, Hist_On_Hand_Qty As OH 
        // FROM wal_main_apr6tojul02_filtered 
        // WHERE Vendor_Name IN ($frameworkList) 
        // AND Hist_On_Hand_Qty - Cycle_Count_Restored < 0
        // AND Date LIKE '$selectedDate'
        // ";

        // $query = "
        // SELECT Date as date, ((SUM(CASE WHEN(Cycle_Count_Restored - Hist_On_Hand_Qty)=0 THEN 1 ELSE 0 END)) / COUNT(SKU))
        // AS exactmatch FROM wal_main_apr6tojul02_filtered GROUP BY Date;
        // ";

        $vendorsQuery = sqlsrv_query($conn, $query);
        if(str_starts_with($selectedDate, 'Select')){
            echo ('Select Date to View Bubble Viz');
        }
        else{
            if($vendorsQuery){
                $dataVen = array();
                while ($row = sqlsrv_fetch_array($vendorsQuery, SQLSRV_FETCH_ASSOC)) {
                    $dataVen[] = $row;
                }
                echo json_encode($dataVen);
            }
        }
            
       
        
    }
    else {
        echo 'Please select "Vendor(s)" and/or "Date" to view chart(s)';
    }

    // sqlsrv_close($conn);

        
?>



