<?php
    $serverName = "oitss5\mssql05,1433"; //serverName\instanceName
    $connectionInfo = array( "Database"=>"RFID_Inventory", "UID"=>"RFID_Inventoryuser", "PWD"=>"pO49nY1xdM");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);
    $vendorName = "";
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
        $vendorName .= $row . ',';
        // echo $row;
        // echo '<option value="'.$row.'">'.$row.'</option>';
    }
    echo $vendorName;
    
?>