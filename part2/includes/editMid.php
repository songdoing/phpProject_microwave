<?php
/*
Identification : editMid.php(part2)
Author : group19  (Wonjin, Paul)
Purpose : This is for validating inputted data and updating the data to the DB regarding Mid info.
*/
header("Content-Type: application/json");
require_once("../includes/db.php");

$db_conn = connect_db();

$paths = array();
$err_msgs = array();
$data = array();


if (isset($_SERVER['REQUEST_METHOD']) &&  $_SERVER['REQUEST_METHOD'] == "POST"){
	$err_msgs = validateInput();
	if ($err_msgs) {
		$data = array("errors" => $err_msgs);
		$data["status"] = "Errors";
		echo json_encode($data);
	} else {
		editMid();
	}
}
	
function editMid() {

	// load all paths and id
	$paths = $_POST['paths'];
	$mid_id = (intval($_POST['updateID']));

	// loop through to save updates
	for ($i = 0; $i < count($paths); $i++) {
		// set vars for save, based on matching mid_id
		if(isset($paths[$i]['mid_id']) && ($paths[$i]['mid_id']) == $mid_id) {
			if(isset($paths[$i]['mid_ground_height']) && !empty($paths[$i]['mid_ground_height'])) {
				$mid_ground_height = trim($paths[$i]['mid_ground_height']);
			} else {
				$err_msgs[] = "Cannot be empty: mid_ground_height";
			}
			if(isset($paths[$i]['mid_terrain_type']) && !empty($paths[$i]['mid_terrain_type'])) {
				$mid_terrain_type = trim($paths[$i]['mid_terrain_type']);
			} else {
				$err_msgs[] = "Cannot be empty: mid_terrain_type";
			}
			if(isset($paths[$i]['mid_obstruction_height']) && !empty($paths[$i]['mid_obstruction_height'])) {
				$mid_obstruction_height = trim($paths[$i]['mid_obstruction_height']);
			} else {
				$err_msgs[] = "Cannot be empty: mid_obstruction_height";
			}
			if(isset($paths[$i]['mid_obstruction_type']) && !empty($paths[$i]['mid_obstruction_type'])) {
				$mid_obstruction_type = trim($paths[$i]['mid_obstruction_type']);
			} else {
				$err_msgs[] = "Cannot be empty: mid_obstruction_type";
			}
		}
	}

	if (empty($err_msgs)) {
		$db_conn = connect_db();
		$qry = "UPDATE path_mid SET mid_ground_height = ".$mid_ground_height.", mid_terrain_type = '".$mid_terrain_type."', mid_obstruction_height = '".$mid_obstruction_height."', mid_obstruction_type = '".$mid_obstruction_type."' where mid_id = ".$mid_id."; ";
		$rs = $db_conn->query($qry);
		if ($rs){
			$data = array("status" => "OK");

		} else {
			$data = array("status" => "NONE");
		}
		// return results
		echo json_encode($data);
		$db_conn->close();

	} else {
		echo json_encode($err_msgs);
	}
}

function validateInput() {
	$err_msgs = array();
	// load all paths and id
	$paths = $_POST['paths'];
	$mid_id = (intval($_POST['updateID']));

	// loop through to find mid path to edit
	for ($i = 0; $i < count($paths); $i++) {
		// set vars for save, based on matching mid_id
		if(isset($paths[$i]['mid_id']) && ($paths[$i]['mid_id']) == $mid_id) {	// loop through to validate updates
        
			//mid_ground_height
			if(isset($paths[$i]['mid_ground_height']) && !empty($paths[$i]['mid_ground_height'])) {
				$mid_ground_height = trim($paths[$i]['mid_ground_height']);
				if(!is_numeric($mid_ground_height)) {
					$err_msgs[] = "The mid_ground_height field should be numeric";	
				} 
			} else {
				$err_msgs[] = "Cannot be empty: mid_ground_height";
			}		
			//mid_terrain_type
			if(isset($paths[$i]['mid_terrain_type']) && !empty($paths[$i]['mid_terrain_type'])) {
				$mid_terrain_type = trim(strtolower($paths[$i]['mid_terrain_type']));
				$values = ["grassland","rough grassland","smooth rock","bare rock","bare earth", "paved surface", "lake", "ocean"];
				if(!in_array($mid_terrain_type, $values)){
					$err_msgs[] = "The mid_terrain_type field is invalid; ";
				}
			} else {
				$err_msgs[] = "Cannot be empty: mid_terrain_type";
			}
			//mid_obstruction_height
			if(isset($paths[$i]['mid_obstruction_height']) && !empty($paths[$i]['mid_obstruction_height'])) {
				$mid_obstruction_height = trim($paths[$i]['mid_obstruction_height']);
				if(!is_numeric($mid_obstruction_height)) {
					$err_msgs[] = "The value of mid_obstruction_height should be numeric; ";
				}
			} else {
				$err_msgs[] = "Cannot be empty: mid_obstruction_height";
			}
			//mid_obstruction_type
			if(isset($paths[$i]['mid_obstruction_type']) && !empty($paths[$i]['mid_obstruction_type'])) {
				$mid_obstruction_type = trim(strtolower($paths[$i]['mid_obstruction_type']));
				$values = ["none","trees","brush","buildings","webbed towers", "solid towers", "power cables"];
				if(!in_array($mid_obstruction_type, $values)){
					$err_msgs[] = "The mid_obstruction_type field is invalid; ";
				}
			} else {
				$err_msgs[] = "Cannot be empty: mid_obstruction_type";
			}
		}
	}
	return $err_msgs; 
}

?>
