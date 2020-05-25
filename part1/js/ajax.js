$(document).ready( function() {
    $("#displayPathForm").submit( function(event) {
		$.post("./includes/displayPathDetail.php", $(this).serialize(), onNewClick);
		event.preventDefault();
    }); 
    
    var onNewClick = function(data) {
        if (data.status == "None") {
			$("#general").html("No paths available");
		} else if (data.status == "OK"){
            pathIndex = 0; 

            // draw first table with general info
            $("#general").html("<h4>General Information</h4><table id=\"table1\" border=\"1\"></table>");
            $("#end").html("<h4>End Point</h4><table id=\"table2\" border=\"1\"></table>");
            $("#mid").html("<h4>Mid Point</h4><table id=\"table3\" border=\"1\"></table>");
       		
           	$("#table1").html("<tr><th>Path Name<th>Operating Frequency</th><th>Description</th><th>Note</th></tr>");
			
            $("#table1").append(
                    "<tr><td width='10%'>" + data.paths[0].path_name 
                    + "</td><td width='10%'>" + data.paths[0].path_frequency
                    + "</td><td width='10%'>" + data.paths[0].path_description
                    + "</td><td width='10%'>" + data.paths[0].path_note + "</td></tr>"
            );
            pathIndex++;
            
            // draw second table with end point info
            $("#table2").html("<tr><th>Distance from the start of the path<th>Ground height</th><th>Antenna height</th><th>Antenna cable type</th><th>Antenna cable length</th></tr>");
            while (pathIndex > 0 && pathIndex <3) {
				$("#table2").append(
                      "<tr><td width='10%'>" + data.paths[pathIndex].end_distance 
                      + "</td><td width='10%'>" + data.paths[pathIndex].end_ground_height
                      + "</td><td width='10%'>" + data.paths[pathIndex].end_antenna_height
                      + "</td><td width='10%'>" + data.paths[pathIndex].end_ant_cable_type
                      + "</td><td width='10%'>" + data.paths[pathIndex].end_ant_cable_length + "</td></tr>"
				);
				pathIndex++;
            }
            // draw second table with end point info
            $("#table3").html("<tr><th>Distance from the start of the path<th>Ground height</th><th>Terrain Type</th><th>Obstruction Height</th><th>Obstruction Type</th></tr>");
            while (pathIndex > 2 && pathIndex < data.paths.length) {
				$("#table3").append(
                      "<tr><td width='10%'>" + data.paths[pathIndex].mid_distance 
                      + "</td><td width='10%'>" + data.paths[pathIndex].mid_ground_height
                      + "</td><td width='10%'>" + data.paths[pathIndex].mid_terrain_type
                      + "</td><td width='10%'>" + data.paths[pathIndex].mid_obstruction_height
                      + "</td><td width='10%'>" + data.paths[pathIndex].mid_obstruction_type + "</td></tr>"
				);
                pathIndex++;
            }
        }
    };
});