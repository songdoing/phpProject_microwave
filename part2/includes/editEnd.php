<?php
/*
Identification : editEnd.php(part2)
Author : group19  (Wonjin, Paul)
Purpose : This is for validating inputted data and updating the data to the DB regarding End info.
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
		editEnd();
	}
}
	
	function editEnd() {
	// load all paths and id
	$paths = $_POST['paths'];
	$end_id = (intval($_POST['updateID']));
		for ($i = 0; $i < count($paths); $i++) {
			// set vars for save, based on matching end_id
			if(isset($paths[$i]['end_id']) && ($paths[$i]['end_id']) == $end_id) {
				if(isset($paths[$i]['end_ground_height']) && !empty($paths[$i]['end_ground_height'])) {
					$end_ground_height = trim($paths[$i]['end_ground_height']);
				} else {
					$err_msgs[] = "Cannot be empty: end_ground_height";
				}

				if(isset($paths[$i]['end_antenna_height']) && !empty($paths[$i]['end_antenna_height'])) {
					$end_antenna_height = trim($paths[$i]['end_antenna_height']);
				} else {
					$err_msgs[] = "Cannot be empty: end_antenna_height";
				}

				if(isset($paths[$i]['end_ant_cable_type']) && !empty($paths[$i]['end_ant_cable_type'])) {
					$end_ant_cable_type = trim($paths[$i]['end_ant_cable_type']);
				} else {
					$err_msgs[] = "Cannot be empty: end_ant_cable_type";
				}

				if(isset($paths[$i]['end_ant_cable_length']) && !empty($paths[$i]['end_ant_cable_length'])) {
					$end_ant_cable_length = trim($paths[$i]['end_ant_cable_length']);
					
				} else {
					$err_msgs[] = "Cannot be empty: end_ant_cable_length";
				}
			}
		}

		if (empty($err_msgs)) {
			$db_conn = connect_db();
			$qry = "UPDATE path_end SET end_ground_height = ".$end_ground_height.", end_antenna_height = '".$end_antenna_height."', end_ant_cable_type = '".$end_ant_cable_type."', end_ant_cable_length = '".$end_ant_cable_length."' where end_id = ".$end_id."; ";
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
	$end_id = (intval($_POST['updateID']));
	// loop through to validate updates
	for ($i = 0; $i < count($paths); $i++) {
		if(isset($paths[$i]['end_id']) && ($paths[$i]['end_id']) == $end_id) {

		//end_ground_height
		if((isset($paths[$i]['end_ground_height'])) && !empty($paths[$i]['end_ground_height'])) {
		   $end_ground_height = trim($paths[$i]['end_ground_height']);
		    if(!is_numeric($end_ground_height)) {
				$err_msgs[] = "The end_ground_height field should be numeric";	
			} 
		} else {
			$err_msgs[] = "Cannot be empty: end_ground_height";
		}

		//end_antenna_height
		if(isset($paths[$i]['end_antenna_height']) && !empty($paths[$i]['end_antenna_height'])) {
			$end_antenna_height = trim($paths[$i]['end_antenna_height']);
			if(!is_numeric($end_antenna_height)) {
				$err_msgs[] = "The type of the field should be numeric";
			} 
		} else {
			$err_msgs[] = "Cannot be empty: end_antenna_height";
		}
 
		//end_ant_cable_type
		if(isset($paths[$i]['end_ant_cable_type']) && !empty($paths[$i]['end_ant_cable_type'])) {
			$end_ant_cable_type = trim($paths[$i]['end_ant_cable_type']);
			$values = ["LDF4-50A","LDF5-50A","LDF-6-50","LDF7-50A","LDF12-50"];
			if(!in_array($end_ant_cable_type, $values)){
				$err_msgs[] = "The value of Antenna cable type is invalid.";
				}
		} else {
			$err_msgs[] = "Cannot be empty: end_ant_cable_type";
		}

		//end_ant_cable_length
		if(isset($paths[$i]['end_ant_cable_length']) && !empty($paths[$i]['end_ant_cable_length'])) {
			$end_ant_cable_length = trim($paths[$i]['end_ant_cable_length']);
			if(!is_numeric($end_ant_cable_length)) {
				$err_msgs[] = "The type of the field should be numeric";		
			} 
		} else {
			$err_msgs[] = "Cannot be empty: end_ant_cable_length";
		}
	}
}
	return ($err_msgs);
}
?>