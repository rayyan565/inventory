<?php
    $serverName = "oitss5\mssql05,1433"; //serverName\instanceName
    $connectionInfo = array( "Database"=>"RFID_Inventory", "UID"=>"RFID_Inventoryuser", "PWD"=>"pO49nY1xdM");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);
    $data = "";
    // get vendors
    $getVendorsquery = "
        SELECT DISTINCT(SKU), RFID, OH, Price
        FROM month_test_data35_prices
        ";
    $vendors = sqlsrv_query($conn, $getVendorsquery);
    $dataVendors = array();
    while ($row = sqlsrv_fetch_array($vendors, SQLSRV_FETCH_ASSOC)) {
        $rowData = $row['SKU']." - ".$row['RFID']." - ".$row['OH']." - ".$row['Price']."\n";
        array_push($dataVendors, $rowData);
    }

    foreach($dataVendors as $row)
    {
        // $data .= $row . ',';
        
        echo $row . "\n";
        // echo '<option value="'.$row.'">'.$row.'</option>';
    }
    // echo $data;
    
?>