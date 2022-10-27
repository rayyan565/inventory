<?php
    $host = "alecsiteserver.mysql.database.azure.com";
    $dbname = "bubble_viz";
    $username = "alecadmin";
    $password = "RFIDlab123!";

    $mysqli = new mysqli(hostname: $host, username: $username, password: $password, database: $dbname);

    $data = "";
    // get vendors
    $getVendorsquery = "
        SELECT DISTINCT(SKU), RFID, OH, Price
        FROM wal_main_apr6tojul02_filtered_prices
        ";
    $vendors = $mysqli->query($getVendorsquery);
    $dataVendors = array();
    while ($row = $vendors->fetch_array(MYSQLI_ASSOC)) {
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