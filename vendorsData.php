<?php
$mysqli = require __DIR__ . "/db.php";
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