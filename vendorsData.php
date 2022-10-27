<?php
    $host = "alecsiteserver.mysql.database.azure.com";
    $dbname = "bubble_viz";
    $username = "alecadmin";
    $password = "RFIDlab123!";

    $mysqli = new mysqli(hostname: $host, username: $username, password: $password, database: $dbname);

    $vendorName = "";
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
        $vendorName .= $row . ',';
        
        // echo $row;
        // echo '<option value="'.$row.'">'.$row.'</option>';
    }
    echo $vendorName;
    
?>