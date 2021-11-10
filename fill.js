
function populateChart(error, data) {
  
    d3.selectAll("svg > *").remove();
    
   // console.log(data);
   if (error) {
       console.error('Error getting or parsing the data.');
       throw error;
   }
   
       data.map(function (d){
 
         d.OH = parseInt(d.OH,10)
         d.RFID = parseInt(d.RFID,10)
         d.Price = parseFloat(d.Price,10)
 
         console.log(d);
         return d; 
         
       })
   // selection.datum() returns the bound datum for the first element in the selection and 
   //  doesn't join the specified array of data with the selected elements
   var chart = bubbleChart().width(innerWidth).height(innerHeight);
   
   d3.select('#chart').datum(data).call(chart);
 
   
 }
 
 document.getElementById('resetButton').onclick = function() {
  
  if (reader.result == null){
    location.reload()
  }
  else {
    d3.selectAll("svg > *").remove();
    let data = csvJSON(reader.result); 
    populateChart(null, data)
    // console.log(data);
    // d3.selectAll("svg > *").remove();
    // populateChart(null, file);
  }
 }
 
 var reader = new FileReader();
     
     function loadFile() {      
       var file = document.querySelector('input[type=file]').files[0];      
       reader.addEventListener("load", parseFile, false);
       if (file) {
         reader.readAsText(file);
       }      
     }
     
     function parseFile(){
      // console.log(reader);
      var doesColumnExist = false;
      
      let data = csvJSON(reader.result); 
      populateChart(null, data)
     }
 
 
 function csvJSON(csv){
   var stringWithFormattedLineBreaks = csv.replace(/(\r\n|\n|\r)/gm, "\n").replace(/\"/g, "");//remove those line breaks
   var lines=stringWithFormattedLineBreaks.split("\n");
 
   var result = [];
 
   var headers=lines[0].split(",");
 
   for(var i=1;i<lines.length;i++){
 
    var obj = {};
    var currentline=lines[i].split(",");

    for(var j=0;j<headers.length;j++){
      obj[headers[j]] = currentline[j];
    }

    result.push(obj);
   }
   
   return result; //JavaScript object
 }
 
 
 
 
 //reading data 
 d3.csv('https://gist.githubusercontent.com/merrickhaigler/62c45d9e0335713e329a67ee7a1b973a/raw/6a14eb2282c61fe0cd3eae820db3fdac75464c48/SKUsTest', populateChart);