<?php
/*
Identification : editGeneral.php(part2)
Author : group19  (Wonjin, Paul)
Purpose : This is for validating inputted data and updating the data to the DB regarding General info.
*/
header("Content-Type: application/json");

require_once("../includes/db.php");

$db_conn = connect_db();
$paths = array();
$err_msgs = array();
$data = array();
$fields = array();

if (isset($_SERVER['REQUEST_METHOD']) &&  $_SERVER['REQUEST_METHOD'] == "POST"){
	$err_msgs = validateInput();
	if ($err_msgs) {
		$data = array("errors" => $err_msgs);
		$data["status"] = "Errors";
		echo json_encode($data);
	} else {
		editGeneral();
	}
}

function editGeneral() {
	$db_conn = connect_db();

	// load path
	$paths = $_POST['paths'][0];

	if(isset($paths['path_id'])) {
		$choice = ($paths['path_id']);
		$path_frequency = $db_conn->real_escape_string(trim($paths['path_frequency']));
		$path_description = $db_conn->real_escape_string(trim($paths['path_description']));
		$path_note = $db_conn->real_escape_string(trim($paths['path_note']));
		$fields = array();

			
		$qry = "UPDATE path SET path_frequency = ".$path_frequency.", path_description = '".$path_description."', path_note = '".$path_note."' where path_id = ".$choice."; ";
		$rs = $db_conn->query($qry); 
		
		if ($rs){
			$data = array("status" => "OK");
		} else {
			$data = array("status" => "dberror");
		}
		echo json_encode($data);
		$db_conn->close();
	}
}

function validateInput() {
/**/
	//path_frequency
	if(isset($_POST['paths'][0]['path_frequency']) && !empty($_POST['paths'][0]['path_frequency'])) {
		$path_frequency = trim($_POST['paths'][0]['path_frequency']);

		if(!is_numeric($path_frequency)) {
			$err_msgs[] = "Requires numeric data: path_frequency; ";
		} 
		else if($path_frequency <1.0 || $path_frequency>100.0) {
			$err_msgs[] = "Requires numeric data between 1.0 and 100.0: path_frequency; ";
		}
	} else {
		$err_msgs[] = "Cannot be empty: path_frequency; ";
	}
	//path_description
	if(isset($_POST['paths'][0]['path_description']) && !empty($_POST['paths'][0]['path_description'])) {
		$path_description = trim($_POST['paths'][0]['path_description']);
		if(strlen($path_description) >255 ) {
			$err_msgs[] = "Requires less than 255 characters: path_description; ";
		} 
	}else {
		$err_msgs[] = "Cannot be empty: path_description; ";
	}
	//path_note
	if(isset($_POST['paths'][0]['path_note'])) {
		$path_note = trim($_POST['paths'][0]['path_note']);
		if(strlen($path_note) > 65534 ) {
			$err_msgs[] = "Requires less than 65534 characters:  path_note; ";
		} 
	}else {
		$err_msgs[] = "Cannot be empty: path_note; ";
	}
	if(isset($err_msgs)) {
		return($err_msgs);
	}
}
?>
