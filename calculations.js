function negScale(OH_array, RFID_array){
  let scale = 1
  if (avgSize(OH_array, RFID_array) < 1000){
    scale = 2
  }
  else if (avgSize(OH_array, RFID_array) < 2000){
    scale = 1.7
  }
  else if (avgSize(OH_array, RFID_array) < 3000){
    scale = 1.1
  }
  else if (avgSize(OH_array, RFID_array) < 6000){
    scale = 1
  }
  else if (avgSize(OH_array, RFID_array) < 10000){
    scale = .75
  }
  else if (avgSize(OH_array, RFID_array) < 15000){
    scale = .5
  }
  else if (avgSize(OH_array, RFID_array) < 30001){
    scale = .25
  }
  else if (avgSize(OH_array, RFID_array) > 30000){
    scale = .1
  }
  else {
    scale = 1
  }
  return scale
}

function scale(OH_array, RFID_array){
  let scale = 1
  if (avgSize(OH_array, RFID_array) < 1000){
    scale = 2
  }
  else if (avgSize(OH_array, RFID_array) < 2000){
    scale = 1.6
  }
  else if (avgSize(OH_array, RFID_array) < 3000){
    scale = 1.4
  }
  else if (avgSize(OH_array, RFID_array) < 6000){
    scale = 1
  }
  else if (avgSize(OH_array, RFID_array) < 10000){
    scale = .85
  }
  else if (avgSize(OH_array, RFID_array) < 15000){
    scale = .7
  }
  else if (avgSize(OH_array, RFID_array) < 30000){
    scale = .5
  }
  else if (avgSize(OH_array, RFID_array) > 30000){
    scale = .3
  }
  else {
    scale = 1
  }
  return scale
}

function avgSize(OH_array, RFID_array){
  size = 0; 

  for (let i = 0; i < RFID_array.length; i++){
    if (OH_array[i] > RFID_array[i]){
      size += OH_array[i];
    }
    else if (OH_array[i] == 0 && RFID_array[i] == 0){
      size +=1
    }
    else {
      size += RFID_array[i];
    }
  }
  
  return size;
}


//calculation for the inner radius of the circle using the smaller value 
function calculateRadius(OH, RFID){
    let radius = Math.sqrt( 1 / Math.PI);
    
    if(OH < 0){
      radius =  Math.sqrt(RFID / Math.PI); // negative OH 
    }
    else if(OH == 0 &&  RFID == 0){ // accurate out of stock 
      radius = Math.sqrt(1 / Math.PI); 
    }
    else if(OH > 0 && RFID == 0){
      radius =  Math.sqrt(OH / Math.PI);  // frozen out of stock 
    }
    else if(OH == 0 && RFID > 0){
      radius =  Math.sqrt(RFID / Math.PI);  // frozen out of stock 
    }
    else if(OH > RFID){
      radius =  (Math.sqrt(OH / Math.PI) + (Math.sqrt(RFID / Math.PI))) / 2; // Overstated 
    } 
    else if(OH < RFID){
      radius =  (Math.sqrt(OH / Math.PI) + (Math.sqrt(RFID / Math.PI))) / 2; //Understated 
    }
    else {
      radius = Math.sqrt(RFID / Math.PI); // Exact Match 
    }
    
    return Math.max(radius, Math.sqrt(1 / Math.PI)) * 5 * scale(OH_array,RFID_array);
}

//calculation for the outer radius of the circle using the larger value 
function calculateStroke(OH, RFID){
  let stroke = 0;
  if (OH == RFID) { //Exact Match
    stroke = 0;
  }
  else if (OH < 0){
    stroke = Math.sqrt(RFID / Math.PI); // negative
  }
  else if(OH > 0 && RFID == 0){
    stroke =  0;  // frozen out of stock 
  }
  else if(OH == 0 && RFID > 0){
    stroke =  0;  // frozen out of stock 
  }
  else if(OH > 0 && RFID == 0){
    stroke =  Math.sqrt(OH / Math.PI);  // frozen out of stock 
  }
  else if(OH > RFID){ // Overstated 
    stroke = Math.abs((Math.sqrt(OH / Math.PI)) - (Math.sqrt(RFID / Math.PI)))
  } 
  else {
    stroke = Math.abs((Math.sqrt(OH / Math.PI)) - (Math.sqrt(RFID / Math.PI)))
  }
  return Math.max(stroke, 0) * 5 * scale(OH_array,RFID_array);
}

function calculateRadiusWithStroke(OH, RFID){
  return Math.max(calculateRadius(OH, RFID) + calculateStroke(OH,RFID) / 2, 0);
}

function calculateRadiusWithoutStroke(OH, RFID){
  return  Math.max(calculateRadius(OH, RFID) - calculateStroke(OH,RFID) / 2, 0);
}


//coloring the circles based on their RFID OH relationship 
function getCircleColor(OH, RFID){
  if(OH < 0){
    color =  "#00A7FA"; // negative OH fully understated (blue)
  }
  else if(OH < 0 &&  RFID > 0 ){ // negative oh with RFID inventory 
    color = "white"; 
  }
  else if(OH > 0 && RFID == 0){
    color =  "#FF3B28";  // frozen out of stock red
  }
  else if(OH == 0 && RFID > 0){
    color =  "#00A7FA";  // frozen out of stock (blue)
  }
  else if(OH == 0 &&  RFID == 0 || OH < 0){ // accurate out of stock 
    color = "#BFBFBF"; 
  }
  else if(OH > 0 && RFID == 0){
    color =  "#D82E3F";  // frozen out of stock 
  }
  else color = "#00FF7F"; 
  
  return color; 
}

// coloring stroke based on over under and exact "#0A51F6" -blue "#D82E3F - red
function getColorStroke(OH, RFID){
  let strokeColor = "#FF3B28"

  if(OH < RFID){
    strokeColor =  "#00A7FA";
    // negative OH 00FF7F (blue)
  }
  else if(OH > 0 && RFID == 0){
    strokeColor =  "#FF3B28";
      // frozen out of stock 
  }
  else if(OH > RFID){
    strokeColor = "#FF3B28";
   // overstated 
  }
  else if (OH < RFID){
    strokeColor = "white";
    // understated 
  }
  return strokeColor; 
}

// function getColorStroke(OH, RFID){
//   stroke
// }

function getSmapeColor(OH,RFID){
  if (calculateSmape(OH,RFID) < .1) {
      color = "#00ff7f";
  }
  else if (calculateSmape(OH,RFID) < .2) {
    color = "#6fec51";
  }
  else if (calculateSmape(OH,RFID) < .3) {
    color = "#96d822";
  }
  else if (calculateSmape(OH,RFID) < .4) {
    color = "#b1c300";
  }
  else if (calculateSmape(OH,RFID) < .5) {
    color = "#c4ad00";
  }
  else if (calculateSmape(OH,RFID) < .6) {
    color = "#d39600";
  }
  else if (calculateSmape(OH,RFID) < .7) {
    color = "#dc7d00";
  }
  else if (calculateSmape(OH,RFID) < .8) {
    color = "#e06413";
  }
  else if (calculateSmape(OH,RFID) < .9) {
    color = "#df4a2c";
  }
  else if (calculateSmape(OH,RFID) <= 1) {
    color = "#d82e3f";
  }
  return color; 
}


function orderSmape(OH,RFID){
  if (calculateSmape(OH,RFID) < .1) {
    position = innerWidth * - .35;
  }
  else if (calculateSmape(OH,RFID) < .2) {
    position = innerWidth * - .25;
  }
  else if (calculateSmape(OH,RFID) < .3) {
    position = innerWidth * - .15;
  }
  else if (calculateSmape(OH,RFID) < .4) {
    position = innerWidth * - .05;
  }
  else if (calculateSmape(OH,RFID) < .5) {
    position = innerWidth *  .03;
  }
  else if (calculateSmape(OH,RFID) < .6) {
    position = innerWidth *  .1;
  }
  else if (calculateSmape(OH,RFID) < .7) {
    position = innerWidth *  .17;
  }
  else if (calculateSmape(OH,RFID) < .8) {
    position = innerWidth *  .23;
  }
  else if (calculateSmape(OH,RFID) < .9) {
    position = innerWidth *  .27;
  }
  else if (calculateSmape(OH,RFID) <= 1) {
    position = innerWidth *  .33;
  }
  return position; 
}

//postion for over and under seperated values
function seperateInventory(OH,RFID) {
  if (RFID > OH) { //Understated
    return innerWidth *  .27;
  }
  else if (OH > RFID) { //Overstated
    return innerWidth * - .27;
  }
  // else if (OH == 0 &&  OH == 0) { //Unstocked
  //   return 0;
  // }
  else{
    return  0; //Exact Match
  }
}

  //postiion for over and under seperated values
  function frozenStock(OH,RFID) {
  if (RFID == 0 && OH > 0) { //Understated
    return innerWidth * -.25;
  }
  else{
    return  innerWidth * .07; //Exact Match
  }
}




//postiion for over and under seperated values
function zeroStock(OH,RFID) {
  if ((RFID == 0 && OH > 0) || (RFID == 0 && OH == 0)) { //Understated
    return innerWidth * -.25;
  }
  else{
    return  innerWidth * .07; //Exact Match
  }
}



function calculateSmape(OH, RFID){
  smape = 0
  if (OH == RFID){
    smape = 0;
  }
  
  else if ((OH > 0 & RFID == 0) || (OH == 0 & RFID > 0) ) {
    smape = 1; 
  }

  else{
    smape = ((Math.abs(OH - RFID)) / (Math.abs(OH) + Math.abs(RFID))) ;
  }

  return smape;
}

function calculateExactMatch(OH_array, RFID_array){
  let matches = 0 

  for (let i = 0; i < RFID_array.length; i++){
    if (OH_array[i] == RFID_array[i]){
      matches ++
    }
  }
  return Math.round(matches / RFID_array.length * 100); 
}

function calculateTotalMag(OH_array, RFID_array){
  let sumOH = 0 
  let sumRFID = 0

  for (let i = 0; i < RFID_array.length; i++){
    sumOH += OH_array[i];
    sumRFID += RFID_array[i];
  }

  return Math.round(sumRFID / sumOH * 100);
}


function calculateSmapeAvg(OH_array, RFID_array){
  let smapeAvg = 0
  let a = 0 
  let b = 0
 

  for (let i = 0; i < RFID_array.length; i++){
    if (OH_array[i] == 0 && RFID_array[i] == 0 ) {
        smapeAvg += 0
        // console.log(smapeAvg);
    }
    else{
      smapeAvg  += ((Math.abs(OH_array[i] - RFID_array[i])) / (Math.abs(OH_array[i]) + Math.abs(RFID_array[i])))
      // console.log(smapeAvg);
    }
    
  }
  return Math.round((1 - (smapeAvg / RFID_array.length)) * 100) ;
}

function calculateOver(OH_array, RFID_array){
  count = 0;

  for (let i = 0; i < RFID_array.length; i++){
    if (OH_array[i]>RFID_array[i]){
      count ++;
    }
  }

  return count;
}

function overPercentage(OH_array, RFID_array){
  percent = calculateOver(OH_array, RFID_array) / RFID_array.length
  return Math.trunc(percent * 100)
}


function calculateEqual(OH_array, RFID_array){
  count = 0;

  for (let i = 0; i < RFID_array.length; i++){
    if (OH_array[i] == RFID_array[i]){
      count ++;
    }
  }

  return count;
}


function equalPercentage(OH_array, RFID_array){
  percent = calculateEqual(OH_array, RFID_array) / RFID_array.length
  return Math.trunc(percent * 100)
}

function calculateUnder(OH_array, RFID_array){
  count = 0;
  for (let i = 0; i < RFID_array.length; i++){
    if (OH_array[i] < RFID_array[i]){
      count ++;
    }
  }
  return count;
}

function underPercentage(OH_array, RFID_array){
  percent = calculateUnder(OH_array, RFID_array) / RFID_array.length
  return Math.trunc(percent * 100)
}


function calculateNonFrozen(OH_array,RFID_array){
  return RFID_array.length - calculateFrozen(OH_array, RFID_array)
}

function Unfrozenpercentage(OH_array,RFID_array){
  return 100 - frozenPercent(OH_array,RFID_array) 
}

function frozenPercent(OH_array,RFID_array){
  percent = calculateFrozen(OH_array,RFID_array) / calculateNonFrozen(OH_array, RFID_array)
  
   percent = Math.trunc(percent * 100 )
  return percent
}


function calculateFrozen(OH_array, RFID_array){
  count = 0;

  for (let i = 0; i < RFID_array.length; i++){
    if (OH_array[i] > 0 && RFID_array[i] == 0){
      count ++;
    }
  }

  return count;
}

function calculateOutofStock(OH_array,RFID_array){
  count = 0;

  for (let i = 0; i < RFID_array.length; i++){
    if (RFID_array[i] == 0 && OH_array[i] == 0 ){
      count ++;
    }
  }

  count = count + calculateFrozen(OH_array, RFID_array)

  return count;
}

function calculateStocked(OH_array,RFID_array){
  
  return RFID_array.length - calculateOutofStock(OH_array, RFID_array) 
} 


function outOfStockPercent(OH_array,RFID_array){
  percent = calculateOutofStock(OH_array,RFID_array) / RFID_array.length;
  return Math.trunc(percent * 100 )   
}

function stockedPercent(OH_array,RFID_array){
  return 100 - outOfStockPercent(OH_array,RFID_array) ;
   
}


