/*
Identification : ajax.js(part2)
Author : group19  (Wonjin, Paul)
Purpose : This is javascript functionalities for Part 2 of the project.
*/
$(document).ready(function(){
    $("#editPathForm").submit( function(event) {
		$.post("./includes/editPathDetail.php", $(this).serialize(), onNewClick);
		event.preventDefault();
    }); 
	
	//when a path is selected and the ajax request has returned the path details
    var onNewClick = function(data) {
		console.log("onNewClick");
        if (data.status == "None") {
			$("#general").html("No paths available");
		} else if (data.status == "OK"){
            pathIndex = 0; 
			console.log(data);

			// empty all tables 
			$(".dataROW").hide();

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
			
			//when the edit btn clicked, end,mid table and edit btn removed, update, cancel btn displayed
            $(document).on('click','#editG', function(){
				$("#genMessages").hide();
				$("#pathList").hide();
				$("#end").hide();
				$("#mid").hide();
				$("#editG").hide();
				$("#updateG").show();
				$("#cancelG").show();
                $("#path_frequency").html("<input type='text' size='10' name ='path_frequency' value='" + data.paths[0].path_frequency + "'/> ");
                $("#path_description").html("<input type='text' size='10' name ='path_description' value='" + data.paths[0].path_description + "'/> ");
				$("#path_note").html("<input type='text' size='40' name ='path_note' value='" + data.paths[0].path_note + "'/> ");
			
				//When Gen Update button is clicked 
				$(document).on('submit','#updateGform', function(){
					console.log("updateG");
					console.log(data);

					// load edited values from textboxes
					data.paths[0].path_frequency = $("input:text[name=path_frequency]").val();
					data.paths[0].path_description = $("input:text[name=path_description]").val();
					data.paths[0].path_note = $("input:text[name=path_note]").val();

					// 
					$.post("./includes/editGeneral.php", data, dbEditGeneral);
					event.preventDefault();
				});
			});


			//When the cancel btn clicked 
			$(document).on('click','#cancelG', function(){
				$("#genMessages").hide();
		        $("#end").show();
                $("#mid").show();
		        $("#editG").show();
		        $("#updateG").hide();
				$("#cancelG").hide();
				$("#pathList").show();
                $("#path_frequency").html(data.paths[0].path_frequency);
                $("#path_description").html(data.paths[0].path_description);
                $("#path_note").html(data.paths[0].path_note);
			});
			
			// ---------------------2--------------------------
			// draw second table with end point info
			drawEndTable(data);
   
			// ---------------------2 . edit btn--------------------------
			pathIndex = 1; 
			while (pathIndex > 0 && pathIndex <3) {
			
			$(document).on('click','#editE_' + data.paths[pathIndex].end_id, function(){
				endIndex = event.target.alt;

				//hide all rows then only show chosen row
				$(".dataROW").hide();
				$("#pathList").hide();
            	$("#general").hide();
            	$("#mid").hide();			
				$("#end_row_" + endIndex).show();
				$("#editE_" + endIndex).hide();                       
				$("#updateE_" + endIndex).show();
				$("#cancelE_" + endIndex).show();

				// find the correct row to display
				for (i=0; i < data.paths.length; i++) {
					if (endIndex==data.paths[i].end_id) {
						// display the row in editable textboxes
						$("#end_distance_" + endIndex).val(data.paths[i].end_distance);
						$("#end_ground_height_" + endIndex).html("<input type='text' size='7' name ='end_ground_height_endIndex' value='" + data.paths[i].end_ground_height + "'/>");
						$("#end_antenna_height_" + endIndex).html("<input type='text' size='7' name ='end_antenna_height_endIndex' value='" + data.paths[i].end_antenna_height + "'/>");
						$("#end_ant_cable_type_" + endIndex).html("<input type='text' size='7' name ='end_ant_cable_type_endIndex' value='" + data.paths[i].end_ant_cable_type + "'/>");
						$("#end_ant_cable_length_" + endIndex).html("<input type='text' size='7' name ='end_ant_cable_length_endIndex' value='" + data.paths[i].end_ant_cable_length + "'/>");				
					} //end if
				} //end for
			// ---------------------2. update btn--------------------------
				$(document).on('click','#updateE_' + endIndex, function(){
					endIndex = event.target.alt;

					// find the correct row to edit
				for (i=0; i < data.paths.length; i++) {
					if (endIndex==data.paths[i].end_id) {

					// load edited values from textboxes
					data.paths[i].end_ground_height = $('input[name=end_ground_height_endIndex]').val();
					data.paths[i].end_antenna_height = $('input[name=end_antenna_height_endIndex]').val();
					data.paths[i].end_ant_cable_type = $('input[name=end_ant_cable_type_endIndex]').val();
					data.paths[i].end_ant_cable_length = $('input[name=end_ant_cable_length_endIndex]').val();
					} //end if
				} //end for
				data.updateID = endIndex;

				// POST using AJAX
				$.post("./includes/editEnd.php", data, dbEditEnd);
				event.preventDefault();
			}); //end update btn

			// ---------------------2 . cancel btn--------------------------
			$(document).on('click','#cancelE_' + endIndex, function(){
				endIndex = event.target.alt;
				$("#endMessages").hide();
				$("#general").show();
				$("#mid").show();
				$("#pathList").show();
				$("#editE_" + endIndex).show();
				$("#cancelE_" + endIndex).hide();
				$("#updateE_" + endIndex).hide();
				drawEndTable(data);
				drawMidTable(data);
			}); //end cancel btn
		}); //end edit btn
	pathIndex++;
	} //end while

			//-------------------------------3---------------------------
            // draw third table with mid point info
			drawMidTable(data);

            //-------------------------------3. edit btn--------------------
			pathIndex = 3;
			while (pathIndex > 2 && pathIndex <data.paths.length) {
				$(document).on('click','#editM_' + data.paths[pathIndex].mid_id, function(){
					midIndex = event.target.alt;
					$(".dataROW").hide();
					$("#general").hide();
					$("#end").hide();
					$("#mid_row_" + midIndex).show();
					$("#editM_" + midIndex).hide();
					$("#updateM_" + midIndex).show();
					$("#cancelM_" + midIndex).show();
                    $("#pathList").hide();
                    for(i=0; i<data.paths.length; i++) {
                        if(midIndex == data.paths[i].mid_id) {
							//display the row in editable textboxes
                            $("#mid_distance_" + midIndex).val(data.paths[i].mid_distance);
                            $("#mid_ground_height_" + midIndex).html("<input type='text' size = '7' name ='mid_ground_height_midIndex' value='" + data.paths[i].mid_ground_height + "'/>");
                            $("#mid_terrain_type_" + midIndex).html("<input type='text' size = '7' name ='mid_terrain_type_midIndex' value='" + data.paths[i].mid_terrain_type + "'/>");
                            $("#mid_obstruction_height_" + midIndex).html("<input type='text' size = '7' name ='mid_obstruction_height_midIndex' value='" + data.paths[i].mid_obstruction_height + "'/>");
                            $("#mid_obstruction_type_" + midIndex).html("<input type='text' size = '7' name ='mid_obstruction_type_midIndex' value='" + data.paths[i].mid_obstruction_type + "'/>");
						}
                    }
                    
            //-------------------------3. update btn -----------------------
            $(document).on('click','#updateM_' + midIndex, function(){
				midIndex = event.target.alt;
				//find the correct row to edit
				for(i=0; i<data.paths.length; i++){
                    if(midIndex == data.paths[i].mid_id) {
						//load edited values from textboxes
						data.paths[i].mid_ground_height = $('input[name=mid_ground_height_midIndex]').val();
						data.paths[i].mid_terrain_type = $('input[name=mid_terrain_type_midIndex]').val();
						data.paths[i].mid_obstruction_height = $('input[name=mid_obstruction_height_midIndex]').val();
						data.paths[i].mid_obstruction_type = $('input[name=mid_obstruction_type_midIndex]').val();
                    }
				}
				// store selected ID for use during AJAX post
				data.updateID = midIndex;

				//Post using AJAX
				$.post("./includes/editMid.php", data, dbEditMid);
				event.preventDefault();
            }); //end update btn
	
		//-------------------------3. cancel btn -----------------------
			$(document).on('click','#cancelM_' + midIndex, function(test){
				midIndex = event.target.alt;
				$("#midMessages").hide();
				$("#general").show();
				$("#end").show();
				$("#pathList").show();
				$("#editM_" + midIndex).show();
				$("#updateM_" + midIndex).hide();
				$("#cancelM_" + midIndex).hide();
				drawEndTable(data);
				drawMidTable(data);
			}); //end cancel btn
		});  //end edit btn
        pathIndex++;
		}  //end while
		} //end else if status OK   
	}; //end NewClick       
    
     //callback functions
    var dbEditGeneral = function(data) {
		$("#genMessages").show();
		console.log("dbEditGeneral");
		console.log(data);
		if (data.status == "OK") {
			$("#genMessages").html("General Info Updated!!");
			setTimeout(()=>{$("#genMessages").hide();},2000);
				
		} else if (data.status == "Errors") {
			$("#genMessages").html("ERROR: " + data.errors);

		}
	};

	var dbEditEnd = function(data) {
		console.log("dbEditEnd");
		console.log(data);
		if (data.status == "OK") {
			$("#endMessages").show();
			$("#endMessages").html("End Point Updated!!");
			setTimeout(()=>{$("#endMessages").hide();},2000);
			
		} else if (data.status == "Errors") {
			$("#endMessages").html("ERROR: " + data.errors);
		}
	};
	
	var dbEditMid = function(data) {
		console.log("dbEditMid");
		console.log(data);
		if (data.status == "OK") {
			$("#midMessages").show();
			$("#midMessages").html("Mid Point Updated!!");
			setTimeout(()=>{$("#midMessages").hide();},2000);
			
		} else if (data.status == "Errors") {
			$("#midMessages").html("ERROR: " + data.errors);
		}
	};
	
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
			pathIndex++;
		} //end while
	};
	
	
	// end of document.ready
});
