/*
Identification : ajax.js(part3)
Author : group19  (Wonjin, Paul)
Purpose : This is javascript functionalities for Part 3 of the project.
*/
$(document).ready(function(){
    $("#calculateForm").submit( function(event) {
		console.log("clicked submit");
		$.post("./includes/displayCalculation.php", $(this).serialize(), onNewClick);
		event.preventDefault();
    }); 
	
	//when a path is selected and the ajax request has returned the path details
    var onNewClick = function(data) {
		console.log("onNewClick");
		console.log(data);
								
        if (data.status == "None") {
			console.log("status=none");
			$("#general").html("No paths available");
			$("#general").show();

		} else if (data.status == "OK"){
            pathIndex = 0; 
			console.log("status=OK");
			console.log(data.appGroundHeight);
			
			// empty all tables 
			$(".dataROW").hide();

			//display the path loss info
			$("#pathAttenuation").show();
			$("#paValue").text(data.pathAttenuation);
			
			//display the graph
			$("#graphContainer").show();
			drawGraph(data);

			// display all 3 tables
			$("#general").show();
			$("#mid").show();
			$("#end").show();
			$(".updateButton").hide();
			$(".cancelButton").hide();

			// load data into general table
			$("#path_name").text(data.paths[0].path_name);
			$("#path_frequency").text(data.paths[0].path_frequency);
			$("#path_description").text(data.paths[0].path_description);
			$("#path_note").text(data.paths[0].path_note);
            pathIndex++;
			
			// ---------------------2--------------------------
			// draw second table with end point info
			drawEndTable(data);

			//-------------------------------3---------------------------
            // draw third table with mid point info
			drawMidTable(data);

		} //end else if status OK   
	}; //end NewClick       
    
     //callback functions
	
	var drawEndTable = function(data) {
		pathIndex = 1;
		while (pathIndex > 0 && pathIndex <3) {
			$("#end_row_" + data.paths[pathIndex].end_id).show();
			$("#end_distance_" + data.paths[pathIndex].end_id).text(data.paths[pathIndex].end_distance);
			$("#end_ground_height_"+ data.paths[pathIndex].end_id).text(data.paths[pathIndex].end_ground_height);
			$("#end_antenna_height_"+ data.paths[pathIndex].end_id).text(data.paths[pathIndex].end_antenna_height);
			$("#end_ant_cable_type_"+ data.paths[pathIndex].end_id).text(data.paths[pathIndex].end_ant_cable_type);
			$("#end_ant_cable_length_"+ data.paths[pathIndex].end_id).text(data.paths[pathIndex].end_ant_cable_length);
			pathIndex++;
		}
	};

	var drawMidTable = function(data) {
		pathIndex = 3;
		while (pathIndex > 2 && pathIndex < data.paths.length) {
			$("#mid_row_" + data.paths[pathIndex].mid_id).show();
			$("#mid_distance_" + data.paths[pathIndex].mid_id).text(data.paths[pathIndex].mid_distance);
			$("#mid_ground_height_"+ data.paths[pathIndex].mid_id).text(data.paths[pathIndex].mid_ground_height);
			$("#mid_terrain_type_"+ data.paths[pathIndex].mid_id).text(data.paths[pathIndex].mid_terrain_type);
			$("#mid_obstruction_height_"+ data.paths[pathIndex].mid_id).text(data.paths[pathIndex].mid_obstruction_height);
			$("#mid_obstruction_type_"+ data.paths[pathIndex].mid_id).text(data.paths[pathIndex].mid_obstruction_type);
			$("#mid_curvature_"+ data.paths[pathIndex].mid_id).text(data.paths[pathIndex].curvature);
			$("#mid_agh_"+ data.paths[pathIndex].mid_id).text(data.paths[pathIndex].agh);
			$("#mid_first_freznel_"+ data.paths[pathIndex].mid_id).text(data.paths[pathIndex].first_freznel);
			$("#mid_total_clearance_"+ data.paths[pathIndex].mid_id).text(data.paths[pathIndex].total_clearance);
			pathIndex++;
		} //end while
	};
	
	var drawGraph = function(data) {
		var chart = new CanvasJS.Chart("graphContainer", {
			animationEnabled: true,
			theme: "light2",
			title:{
				text: data.paths[0].path_name + " with curvature " + data.factor
			},
			axisX: {
				valueFormatString: "0.000",
				includeZero: true
			},
			axisY:{
				includeZero: true
			},
			data: [
			  {        
				type: "line",
				showInLegend: true,
				name : "Path",
				indexLabelFontSize: 16,
				dataPoints: data.antennaeHeightArr
					
			  },	
			  {        
				type: "line",
				showInLegend: true,
				name : "Gnd+Obs",
				indexLabelFontSize: 16,
				dataPoints: data.appGroundHeight
				
			  },
			  {        
				type: "line",
				showInLegend: true,
				name : "Gnd+Obs+1stFreznel",
				indexLabelFontSize: 16,
				dataPoints: data.totalAppHeight
				
			  }
			  
			]
		});
		chart.render();
	}
	// end of document.ready
});
