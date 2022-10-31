<?php
$mysqli = require __DIR__ . "/db.php";
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

    if(isset($_POST["framework"])){
            $framework = '';
            $selectedDate = '';

            if(isset($_POST["framework"])){
                foreach($_POST["framework"] as $row){
                    $framework .= $row . ',';
                }

            }
                
            $framework = substr($framework, 0, -1);
            $frameworkList = "'" . str_replace(",","','", $framework) . "'";
            
            // line chart 
            $linesQuery = "
            SELECT Date as date, 
                (
                    (SUM(
                        CASE WHEN(RFID - OH)=0
                            THEN 1.000
                            ELSE 0.000 END
                        )
                    ) 
                    / 
                    COUNT(SKU)
                ) AS exactmatch, 
                (
                    (SUM(
                        CASE WHEN(RFID - OH)<0
                            THEN 1.000
                            ELSE 0.000 END
                        )
                    ) 
                    / 
                    COUNT(SKU)
                ) AS overStated,
                (
                    (SUM(
                        CASE WHEN(RFID - OH)>0
                            THEN 1.000
                            ELSE 0.000 END
                        )
                    ) 
                    / 
                    COUNT(SKU)
                ) AS underStated
                FROM wal_main_apr6tojul02_filtered_prices 
                GROUP BY Date
                ORDER BY Date;
            ";

            $lineVendorsQuery = "
                SELECT Date as date, 
                (
                    (SUM(
                        CASE WHEN(RFID - OH)=0
                            THEN 1.000
                            ELSE 0.000 END
                        )
                    ) 
                    / 
                    COUNT(SKU)
                ) AS exactmatch, 
                (
                    (SUM(
                        CASE WHEN(RFID - OH)<0
                            THEN 1.000
                            ELSE 0.000 END
                        )
                    ) 
                    / 
                    COUNT(SKU)
                ) AS overStated,
                (
                    (SUM(
                        CASE WHEN(RFID - OH)>0
                            THEN 1.000
                            ELSE 0.000 END
                        )
                    ) 
                    / 
                    COUNT(SKU)
                ) AS underStated
                FROM wal_main_apr6tojul02_filtered_prices 
                WHERE Vendor_Name IN ($frameworkList)
                GROUP BY Date
                ORDER BY Date;
            ";

        $queryRes = $mysqli->query($lineVendorsQuery);

        $rows = array();
        $table = array();

        $table['cols'] = array(
        array(
        'label' => 'Date', 
        'type' => 'date'
        ),
        array(
        'label' => 'Exact Match', 
        'type' => 'number'
        ),
        array(
            'label' => 'Over Stated', 
            'type' => 'number'
        ),
        array(
            'label' => 'Under Stated', 
            'type' => 'number'
            )
        );

        while($row = $queryRes->fetch_array(MYSQLI_ASSOC)){
        $row["date"] = ($row["date"]->modify("-1 months"))->format("Y,m,d,h,m,s");

        $datetime = explode(".", $row["date"]);

        $sub_array = array();

        $sub_array[] = array(
            "v" => 'Date(' . $datetime[0] . '000)'
        );
        $sub_array[] = array(
            "v" => $row["exactmatch"]
        );
        $sub_array[] = array(
            "v" => $row["overStated"]
        );
        $sub_array[] = array(
            "v" => $row["underStated"]
        );
        $rows[] = array(
            "c" => $sub_array
        );
        $table['rows'] = $rows;
        $jsonTable = json_encode($table);
        }

        echo $jsonTable;

    }
    
?>