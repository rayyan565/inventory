<?php
 $host = "alecsiteserver.mysql.database.azure.com";
 $dbname = "bubble_viz";
 $username = "alecadmin";
 $password = "RFIDlab123!";

 $mysqli = new mysqli(hostname: $host, username: $username, password: $password, database: $dbname);

  
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
        SELECT SKU, RFID, OH, Price
        FROM wal_main_apr6tojul02_filtered_prices 
        WHERE Vendor_Name IN ($frameworkList) 
        AND Date LIKE '$selectedDate'
        ";



        // $query = "
        // SELECT SKU As SKU, RFID, OH 
        // FROM wal_main_apr6tojul02_filtered_prices 
        // WHERE Vendor_Name IN ($frameworkList) 
        // AND OH - RFID < 0
        // AND Date LIKE '$selectedDate'
        // ";

        // $query = "
        // SELECT Date as date, ((SUM(CASE WHEN(RFID - OH)=0 THEN 1 ELSE 0 END)) / COUNT(SKU))
        // AS exactmatch FROM wal_main_apr6tojul02_filtered_prices GROUP BY Date;
        // ";

        $vendorsQuery = $mysqli->query($query);

        if(str_starts_with($selectedDate, 'Select')){
            echo ('Select Date to View Bubble Viz');
        }
        else{
            if($vendorsQuery){
                $dataVen = array();
                while ($row = $vendorsQuery->fetch_array(MYSQLI_ASSOC)) {
                    $dataVen[] = $row;
                }
                echo json_encode($dataVen);
            }
        }
            
       
        
    }
    // else {
    //     echo 'Please select "Vendor(s)" and/or "Date" to view chart(s)';
    // }

    // sqlsrv_close($conn);

        
?>



