const width = innerWidth;
const height = innerHeight;
let reset = 0; 
let click = "measures";
var OH_array = [];
var RFID_array = [];


function bubbleChart() {
    var width = 600,
        height = 400,
        columnForColors = "category",
        columnForRadius = "views";

    function chart(selection) {
        var data = selection.datum();
        var div = selection,
            svg = div.selectAll('svg');
        svg.attr('width', width).attr('height', height);

        
        OH_array = [];
        RFID_array = [];
        //filling in the arrays 
        data.forEach(function(item){
            
            OH_array.push(item.OH);
            RFID_array.push(item.RFID); 

         })
         avgSize(OH_array, RFID_array)
        //building tooltip
        var tooltip = selection
            .append("div")
            .style("position", "absolute")
            .style("visibility", "hidden")
            .style("color", "white")
            .style("padding", "8px")
            .style("background-color", "#626D71")
            .attr("stroke", "12")
            .style("border-radius", "6px")
            .style("text-align", "left")
            .style("font-family", "calibri")
            .style("width", "150px")
            .text("");

        
        //creating the circles 
        var node = svg.selectAll("circle")
            .data(data)
            .enter()
            .append("circle")
            .attr('r', (d) => calculateRadius(d.OH,d.RFID))
            .style("fill", (d)=> getCircleColor(d.OH, d.RFID))
            .attr("stroke", (d) => getColorStroke(d.OH, d.RFID))
            .style("stroke-width",(d) => calculateStroke(d.OH, d.RFID))
            .attr('transform', 'translate(' + [width / 2, height / 2] + ')')
            .on("mouseover", function(d) {
                tooltip.html("RFID: " + d.RFID + "<br>" + "OH: "+ d.OH + "<br>" + "SKU: "+ d.SKU1);
                return tooltip.style("visibility", "visible");
            })
            .on("mousemove", function() {
                return tooltip.style("top", (d3.event.pageY - 30) + "px").style("left", (d3.event.pageX + 10) + "px");
            })
            .on("mouseout", function() {
                return tooltip.style("visibility", "hidden");
            });


        //first circles gravity     
        var simulation = d3.forceSimulation(data)
        .force("charge", d3.forceManyBody().strength(-6 * negScale(OH_array, RFID_array)))
        .force("collide", d3.forceCollide().strength(.8).radius((g) => 
            calculateRadiusWithStroke(g.OH, g.RFID) + 1.3).iterations(1)) // Force that avoids circle overlapping
        .force("x", d3.forceX())
        .force("y", d3.forceY())
        .on("tick", ticked);

        function ticked(e) {
            node.attr("cx", function(d) {
                    return d.x;
                })
                .attr("cy", function(d) {
                    return d.y;
                });
        }
        
        document.getElementById('file').onclick = function() {
            svg.selectAll('text')
                .attr("opacity","0")
                click = "measures";
        }

       //text  
       document.getElementById('Data').onclick = function() {
            svg.selectAll('text')
            .attr("opacity","0")
            // working with text data
            if (click == "measures"){
                svg.append('text')
                    .attr('x', innerWidth *  .05)
                    .attr('y',  innerHeight *   .85)
                    .style("text-anchor", "center")
                    .attr('stroke', '#949494')
                    .attr('fill', '#949494')
                    .text('Exact Match= ' + calculateExactMatch(OH_array, RFID_array) + '%')
                    .attr("opacity","1")
                    .style("font-size", "34px")
                    
                
                svg.append('text')
                    .attr('x', innerWidth *  .05)
                    .attr('y',  innerHeight *   .9)
                    .style("text-anchor", "center")
                    .attr('stroke', '#949494')
                    .attr('fill', '#949494')
                    .text('Total Magnitude= '+ calculateTotalMag(OH_array, RFID_array) + '%')
                    .attr("opacity","1")
                    .style("font-size", "34px")
                    .style('font-style', 'serif')

                svg.append('text')
                    .attr('x', innerWidth  - 320)
                    .attr('y',  innerHeight *   .9 )
                    .style("text-anchor", "center")
                    .attr('stroke', '#949494')
                    .attr('fill', '#949494')
                    .text('SKUs Total= ' + RFID_array.length)
                    .attr("opacity","1")
                    .style("font-size", "34px")
                    click = "H_measures";
            
            }
            else if (click == "H_measures"){
                svg.selectAll('text')
                .attr("opacity","0")
                click = "measures";
            }
            
       }
       

         //When the user selects to split up the circles based on inventory 
        document.getElementById('seperate').onclick = function() {
            click = "seperate"
            svg.selectAll('text')
                .attr("opacity","0")

            document.getElementById('Data').onclick = function() {
                // working with text data
                if (click == "seperate"){
                    svg.append('text')
                        .attr('x', innerWidth *  .11)
                        .attr('y',  innerHeight *   .1)
                        .style("text-anchor", "center")
                        .attr('stroke', '#949494')
                        .attr('fill', '#949494')
                        .text(calculateOver(OH_array, RFID_array) + ' Overstated SKUs ' )
                        .attr("opacity","1")
                        .style("font-size", "34px")
                        click = "H_seperate";
                    svg.append('text')
                        .attr('x', innerWidth *  .19)
                        .attr('y',  innerHeight *   .9)
                        .style("text-anchor", "center")
                        .attr('stroke', '#949494')
                        .attr('fill', '#949494')
                        .text(overPercentage(OH_array, RFID_array) + '%' )
                        .attr("opacity","1")
                        .style("font-size", "34px")
                        click = "H_seperate";
                    svg.append('text')
                        .attr('x', innerWidth *  .43)
                        .attr('y',  innerHeight *   .1)
                        .style("text-anchor", "center")
                        .attr('stroke', '#949494')
                        .attr('fill', '#949494')
                        .text(calculateEqual(OH_array, RFID_array) + ' Equal SKUs' )
                        .attr("opacity","1")
                        .style("font-size", "34px")
                        click = "H_seperate";
                    svg.append('text')
                        .attr('x', innerWidth *  .49)
                        .attr('y',  innerHeight *   .9)
                        .style("text-anchor", "center")
                        .attr('stroke', '#949494')
                        .attr('fill', '#949494')
                        .text(equalPercentage(OH_array, RFID_array) + '%' )
                        .attr("opacity","1")
                        .style("font-size", "34px")
                        click = "H_seperate";
                    svg.append('text')
                        .attr('x', innerWidth *  .68)
                        .attr('y',  innerHeight *   .1)
                        .style("text-anchor", "center")
                        .attr('stroke', '#949494')
                        .attr('fill', '#949494')
                        .text(calculateUnder(OH_array, RFID_array) + ' Understated SKUs' )
                        .attr("opacity","1")
                        .style("font-size", "34px")
                        click = "H_seperate";
                    svg.append('text')
                        .attr('x', innerWidth *  .785)
                        .attr('y',  innerHeight *   .9)
                        .style("text-anchor", "center")
                        .attr('stroke', '#949494')
                        .attr('fill', '#949494')
                        .text(underPercentage(OH_array, RFID_array) + '%' )
                        .attr("opacity","1")
                        .style("font-size", "34px")
                        click = "H_seperate";
                
                }
                else if (click == "H_seperate"){
                    svg.selectAll('text')
                    .attr("opacity","0")
                    click = "seperate";
                }
                
           }

            //Features of the forces applied to the nodes:
            var simulation = d3.forceSimulation(node)
            .force("charge", d3.forceManyBody().strength(-4* negScale(OH_array,RFID_array)))
            .force("collide", d3.forceCollide().strength(.6).radius((g) => 
                calculateRadiusWithStroke(g.OH, g.RFID)+ .5))
            .force("x", d3.forceX((d) => seperateInventory(d.OH,d.RFID)))
            .force("y", d3.forceY())
            .on("tick", ticked);

            simulation
                .nodes(data)
                .on("tick", function(d){
                    node
                        .attr("cx", function(d){ return d.x; })
                        .attr("cy", function(d){ return d.y; })
                });
            }
        
        //to display the frozen out of stock skus
        document.getElementById('frozen').onclick = function() {
            //workinig with text data
            svg.selectAll('text')
            .attr("opacity","0")
            click = "frozen"; 
            if (click == "frozen")
                document.getElementById('Data').onclick = function() {
                    if (click == "frozen"){
                        svg.append('text')
                            .attr('x', innerWidth *  .11)
                            .attr('y',  innerHeight *   .75)
                            .style("text-anchor", "center")
                            .attr('stroke', '#949494')
                            .attr('fill', '#949494')
                            .text('Frozen SKU Total = ' + calculateFrozen(OH_array, RFID_array))
                            .attr("opacity","1")
                            .style("font-size", "34px")

                        svg.append('text')
                            .attr('x', innerWidth *  .19)
                            .attr('y',  innerHeight *   .81)
                            .style("text-anchor", "center")
                            .attr('stroke', '#949494')
                            .attr('fill', '#949494')
                            .text(frozenPercent(OH_array, RFID_array) + '%')
                            .attr("opacity","1")
                            .style("font-size", "34px")   

                        svg.append('text')
                            .attr('x', innerWidth *  .45)
                            .attr('y',  innerHeight *   .05)
                            .style("text-anchor", "center")
                            .attr('stroke', '#949494')
                            .attr('fill', '#949494')
                            .text('Unfrozen SKU Total = ' + calculateNonFrozen(OH_array, RFID_array))
                            .attr("opacity","1")
                            .style("font-size", "34px")

                        svg.append('text')
                            .attr('x', innerWidth *  .55)
                            .attr('y',  innerHeight *   .1)
                            .style("text-anchor", "center")
                            .attr('stroke', '#949494')
                            .attr('fill', '#949494')
                            .text(Unfrozenpercentage(OH_array, RFID_array) + '%')
                            .attr("opacity","1")
                            .style("font-size", "34px")   
                            
                            click = "H_frozen";  
                    }
                    else if (click == "H_frozen"){
                        svg.selectAll('text')
                        .attr("opacity","0")
                        click = "frozen";
                        
                    }
            }
            //Features of the forces applied to the nodes:
            var simulation = d3.forceSimulation(node)
            .force("charge", d3.forceManyBody().strength(-6* negScale(OH_array,RFID_array)))
            .force("collide", d3.forceCollide().strength(.8).radius((g) => 
                calculateRadiusWithStroke(g.OH, g.RFID)+ .5))
            .force("x", d3.forceX((d) => frozenStock(d.OH,d.RFID)))
            .force("y", d3.forceY())
            .on("tick", ticked);

            simulation
                .nodes(data)
                .on("tick", function(d){
                    node
                        .attr("cx", function(d){ return d.x; })
                        .attr("cy", function(d){ return d.y; })
                });
        }
        
        //to display the out of stock skus
        
        document.getElementById('Out-of-Stock').onclick = function() {
            //workinig with text data
            svg.selectAll('text')
            .attr("opacity","0")
            click = "Out-of-Stock"; 
            if (click == "Out-of-Stock")
                document.getElementById('Data').onclick = function() {
                    if (click == "Out-of-Stock"){
                        svg.append('text')
                            .attr('x', innerWidth *  .08)
                            .attr('y',  innerHeight *   .75)
                            .style("text-anchor", "center")
                            .attr('stroke', '#949494')
                            .attr('fill', '#949494')
                            .html('Out-Of-Stock SKU Total = ' + "<br>" + calculateOutofStock(OH_array,RFID_array))
                            .attr("opacity","1")
                            .style("font-size", "34px")

                        svg.append('text')
                            .attr('x', innerWidth *  .2)
                            .attr('y',  innerHeight *   .81)
                            .style("text-anchor", "center")
                            .attr('stroke', '#949494')
                            .attr('fill', '#949494')
                            .text(outOfStockPercent(OH_array, RFID_array) + '%')
                            .attr("opacity","1")
                            .style("font-size", "34px") 

                        svg.append('text')
                            .attr('x', innerWidth *  .45)
                            .attr('y',  innerHeight *   .05)
                            .style("text-anchor", "center")
                            .attr('stroke', '#949494')
                            .attr('fill', '#949494')
                            .text('Stocked SKU Total = ' + calculateStocked(OH_array, RFID_array))
                            .attr("opacity","1")
                            .style("font-size", "34px")
                        svg.append('text')
                            .attr('x', innerWidth *  .55)
                            .attr('y',  innerHeight *   .11)
                            .style("text-anchor", "center")
                            .attr('stroke', '#949494')
                            .attr('fill', '#949494')
                            .text(stockedPercent(OH_array, RFID_array)+'%')
                            .attr("opacity","1")
                            .style("font-size", "34px")
                            click = "H_Out-of-Stock";
  
                    }
                    else if (click == "H_Out-of-Stock"){
                        svg.selectAll('text')
                        .attr("opacity","0")
                        click = "Out-of-Stock";
                    }
            }
            //Features of the forces applied to the nodes:
            var simulation = d3.forceSimulation(node)
            .force("charge", d3.forceManyBody().strength(-4.2* negScale(OH_array,RFID_array)))
            .force("collide", d3.forceCollide().strength(.92).radius((g) => 
                calculateRadiusWithStroke(g.OH, g.RFID)+ .5))
            .force("x", d3.forceX((d) => zeroStock(d.OH,d.RFID)))
            .force("y", d3.forceY())
            .on("tick", ticked);

            simulation
                .nodes(data)
                .on("tick", function(d){
                    node
                        .attr("cx", function(d){ return d.x; })
                        .attr("cy", function(d){ return d.y; })
                });
        }


        //to display smape heatmap 
        document.getElementById('smapeHeatmap').onclick = function() {
            if (reset == 0){
                svg.selectAll("circle")
                    .style("fill",(d)=> getSmapeColor(d.OH, d.RFID))
                    .attr("stroke", (d)=> getSmapeColor(d.OH, d.RFID))
                reset = 1
            }
            else{
                svg.selectAll("circle")
                .style("fill", (d)=> getCircleColor(d.OH, d.RFID))
                .attr("stroke", (d) => getColorStroke(d.OH,d.RFID))
                reset = 0
            }     
        }

        document.getElementById('orderSmape').onclick = function() {
            //workinig with text data
            svg.selectAll('text')
            .attr("opacity","0")
            click = "error"; 
            if (click == "error"){
                document.getElementById('Data').onclick = function() {
                    if (click == "error"){
                        svg.append('text')
                            .attr('x', innerWidth *  .1)
                            .attr('y',  innerHeight *   .85)
                            .style("text-anchor", "center")
                            .attr('stroke', '#949494')
                            .attr('fill', '#949494')
                            .text('0%')
                            .attr("opacity","1")
                            .style("font-size", "34px")

                        svg.append('text')
                            .attr('x', innerWidth *  .28)
                            .attr('y',  innerHeight *   .85)
                            .style("text-anchor", "center")
                            .attr('stroke', '#949494')
                            .attr('fill', '#949494')
                            .text('25%')
                            .attr("opacity","1")
                            .style("font-size", "34px")

                        svg.append('text')
                            .attr('x', innerWidth *  .48)
                            .attr('y',  innerHeight *   .85)
                            .style("text-anchor", "center")
                            .attr('stroke', '#949494')
                            .attr('fill', '#949494')
                            .text('50%')
                            .attr("opacity","1")
                            .style("font-size", "34px")

                        svg.append('text')
                            .attr('x', innerWidth *  .465)
                            .attr('y',  innerHeight *   .92)
                            .style("text-anchor", "right")
                            .attr('stroke', '#949494')
                            .attr('fill', '#949494')
                            .text('(Error)')
                            .attr("opacity","1")
                            .style("font-size", "34px")

                        svg.append('text')
                            .attr('x', innerWidth *  .65)
                            .attr('y',  innerHeight *   .85)
                            .style("text-anchor", "center")
                            .attr('stroke', '#949494')
                            .attr('fill', '#949494')
                            .text('75%')
                            .attr("opacity","1")
                            .style("font-size", "34px")

                        svg.append('text')
                            .attr('x', innerWidth *  .815)
                            .attr('y',  innerHeight *   .85)
                            .style("text-anchor", "center")
                            .attr('stroke', '#949494')
                            .attr('fill', '#949494')
                            .text('100%')
                            .attr("opacity","1")
                            .style("font-size", "34px")
                            click = "H_error";
    
                    }
                    else if (click == "H_error"){
                        svg.selectAll('text')
                        .attr("opacity","0")
                        click = "error";
                    }
                }
            }
            //Features of the forces applied to the nodes:
            var simulation = d3.forceSimulation(node)
            .force("charge", d3.forceManyBody().strength(-2* negScale(OH_array,RFID_array)))
            .force("collide", d3.forceCollide().strength(.8).radius((g) => 
                calculateRadiusWithStroke(g.OH, g.RFID)+ .5).iterations(1))
            .force("x", d3.forceX((d) => orderSmape(d.OH,d.RFID)))
            // .force("x", d3.forceX())
            .force("y", d3.forceY().strength(.05))
            
            simulation
                .nodes(data)
                .on("tick", function(d){
                    node
                        .attr("cx", function(d){ return d.x; })
                        .attr("cy", function(d){ return d.y; })
                });
        }
        //to bring it all back together 
        document.getElementById('collapse').onclick = function() {
            svg.selectAll('text')
            .attr("opacity","0")
            click = "measures";

            document.getElementById('Data').onclick = function() {
                svg.selectAll('text')
                .attr("opacity","0")
                // working with text data
                if (click == "measures"){
                    svg.append('text')
                        .attr('x', innerWidth *  .05)
                        .attr('y',  innerHeight *   .85)
                        .style("text-anchor", "center")
                        .attr('stroke', '#949494')
                        .attr('fill', '#949494')
                        .text('Exact Match= ' + calculateExactMatch(OH_array, RFID_array) + '%')
                        .attr("opacity","1")
                        .style("font-size", "34px")
                        click = "H_measures";
                    
                    svg.append('text')
                        .attr('x', innerWidth *  .05)
                        .attr('y',  innerHeight *   .9)
                        .style("text-anchor", "center")
                        .attr('stroke', '#949494')
                        .attr('fill', '#949494')
                        .text('Total Magnitude= '+ calculateTotalMag(OH_array, RFID_array) + '%')
                        .attr("opacity","1")
                        .style("font-size", "34px")
                        click = "H_measures";
    
                    svg.append('text')
                        .attr('x', innerWidth *  .8)
                        .attr('y',  innerHeight *   .9)
                        .style("text-anchor", "center")
                        .attr('stroke', '#949494')
                        .attr('fill', '#949494')
                        .text('SKUs Total= ' + RFID_array.length)
                        .attr("opacity","1")
                        .style("font-size", "34px")
                        click = "H_measures";
                
                }
                else if (click == "H_measures"){
                    svg.selectAll('text')
                    .attr("opacity","0")
                    click = "measures";
                }
                
           }
            //Features of the forces applied to the nodes:
            var simulation = d3.forceSimulation(node)
            .force("charge", d3.forceManyBody().strength(-5* negScale(OH_array,RFID_array)))
            .force("collide", d3.forceCollide().strength(.9).radius((g) => 
                calculateRadiusWithStroke(g.OH, g.RFID)+ .5))
            .force("x", d3.forceX())
            .force("y", d3.forceY())
            
            simulation
                .nodes(data)
                .on("tick", function(d){
                    node
                        .attr("cx", function(d){ return d.x; })
                        .attr("cy", function(d){ return d.y; })
                });
               
        }

        // document.getElementById('resetButton').onclick = function() {
            // location.reload(); 
        //     return false;
        // }

        

    }
 
    chart.width = function(value) {
        if (!arguments.length) { console.log(width); return width; }
        width = value;
        return chart;
    }
    chart.height = function(value) {
        if (!arguments.length) { console.log(height); return height; }
        height = value;
        return chart;
    }
    return chart;
}

