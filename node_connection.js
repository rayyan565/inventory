var Connection = require('tedious').Connection;  
var config = {  
    server: 'oitss5\mssql05,1433',  //update me
    authentication: {
        type: 'default',
        options: {
            userName: 'RFID_Inventoryuser', //update me
            password: 'pO49nY1xdM'  //update me
        }
    },
    options: {
        // If you are on Microsoft Azure, you need encryption:
        encrypt: true,
        database: 'RFID_Inventory'  //update me
    }
}; 
var connection = new Connection(config);  
connection.on('connect', function(err) {  
    // If no error, then good to proceed.  
    console.log("Connected");  
    executeStatement();  
});  

connection.connect();

var Request = require('tedious').Request;  
var TYPES = require('tedious').TYPES;  

function executeStatement() {  
    request = new Request("SELECT COUNT(DISTINCT(SKU)) FROM wal_main_apr6tojul02_filtered_prices;", function(err) {  
    if (err) {  
        console.log(err);}  
    });  
    console.log('hit the function'); 
    var result = "";  
    request.on('row', function(columns) {  
        columns.forEach(function(column) {  
            if (column.value === null) {  
            console.log('NULL');  
            } else {  
            result+= column.value + " ";  
            }  
        });  
        console.log(result); 
        result ="";  
    });  

    request.on('done', function(rowCount, more) {  
    console.log(rowCount + ' rows returned');  
    });  
    
    // Close the connection after the final event emitted by the request, after the callback passes
    request.on("requestCompleted", function (rowCount, more) {
        connection.close();
    });
    connection.execSql(request);  
}
