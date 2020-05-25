<?php
	require_once("../includes/db.php");


function loadPathForm(){
?>
	<div id="wrapper" class="container">
    <div id="upload" class="container">
		<h2>Select the file containing the path data </h2>
        <form method="POST" name="loadDataForm" id="loadDataForm" enctype="multipart/form-data">
            <input type="file" name="pathFile" class="upload" id= "pathFile" accept="text/csv">
            <input type="submit" class="button" name="uploadPathFile" id="uploadPathFile" value="Upload">
        </form>
        <a href="../index.php"> Return to Main Main</a>

	</div>
	</div>
<?php
}
?>
<?php
function validatePathFile(){
	$err_msgs = array();
	if (isset($_FILES['pathFile'])){
		if (isset($_FILES['pathFile']['error']) && $_FILES['pathFile']['error'] != 0 ){
			$err_msgs[] = "Error occured while uploading the file. The error code is ".$_FILES['pathFile']['error'];
		}
		if (isset($_FILES['pathFile']['size']) && $_FILES['pathFile']['size'] == 0){
			$err_msgs[] = "No contents in the uploaded file, The server received a file size of 0";
		}
		if (isset($_FILES['pathFile']['name']) && !empty($_FILES['pathFile']['name'])){
			if (!(strtolower(pathinfo($_FILES['pathFile']['name'], PATHINFO_EXTENSION)) == "csv")
				|| $_FILES['pathFile']['type'] != "text/csv"){
				$err_msgs[] = "The uploaded file is not a CSV file.";
			}
		} else {
			$err_msgs[] = "No name received for the uploaded file.";
		}
		if (isset($_FILES['pathFile']['tmp_name']) && !empty($_FILES['pathFile']['tmp_name'])){
			if(!file_exists($_FILES['pathFile']['tmp_name'])){
				$err_msgs[] = "Temporary file does not exist";
			}
		} else {
			$err_msgs[] = "Temporary file name not defined for uploaded file.";
		}
	} else {
		$err_msgs[] = "No file was uploaded";
	}
	return $err_msgs;
}
?>
<?php
function savePathData(){
	$newName = moveUploadFile();
	if ($newName == FALSE){
		return"FAILED TO MOVE UPLOAD FILE";
	}

	$db_conn = new mysqli("localhost", "microwave", "!Microwave2!", "microwave_paths");
	if ($db_conn->connect_errno){
		echo "<p>Failed to connect to database</p>\n";
		echo "<p>Error ".$db_conn->connect_errno."</p>\n";
		echo "<p>Message ".$db_conn->connect_error."</p>\n";
		return "FAILED TO OPEN DATABASE";
	}
	if(!$db_conn->set_charset("utf8")){
		echo "<p>Error setting database configuration</p>\n";
		return "FAILED TO SET DB CHARSET";
	}

	$fh =  fopen($newName, "r");
	if (!$fh){
		$db_conn->close();
		return "COULD NOT OPEN PATH DATA FILE";
	}

	$db_conn->autocommit(FALSE);
	$db_conn->begin_transaction();

	$pathID = null;

// Read in the path name and description record and add it to the database
	$data = fgetcsv($fh);
	if ($data){
		if ((!empty($data[0]) && strlen($data[0]) > 0)
			&& (!empty($data[1]) && is_numeric($data[1]) && $data[1] >= 1.0)
			&& (!empty($data[2]) && strlen($data[2]) > 0)){
			
			$qry = "insert into path set ";
			$qry .= "path_name='".$db_conn->real_escape_string($data[0])."', ";
			$qry .= "path_frequency='".$db_conn->real_escape_string($data[1])."', ";
			$qry .= "path_file_name='$newName', ";
			$qry .= "path_description='".$db_conn->real_escape_string($data[2]);
			if (isset($data[3]) && strlen($data[3]) > 0){
				$qry .= "',path_note='".base64_encode($data[3])."';";
			} else {
				$qry .= "';";
			}
			$db_conn->query($qry);
			if ($db_conn->errno){
				echo "<p>Failed to insert path name record</p>\n";
				echo "<p>Error ".$db_conn->errno."</p>\n";
				echo "<p>Message ".$db_conn->error."</p>\n";
				fclose($fh);
				$db_conn->rollback();
				$db_conn->close();
				return "FAILED TO INSERT PATH NAME RECORD";
			}
			$pathID = $db_conn->insert_id;
		} else {
			fclose($fh);
			$db_conn->rollback();
			$db_conn->close();
			return "Invalid path name data";
		}
	}

// Read in the first path end point record and add it to the database
	$data = fgetcsv($fh);
	if ($data){
		if ((is_numeric($data[0])  && $data[0] >= 0.0)
			&& (is_numeric($data[1]))
			&& (is_numeric($data[2]) && $data[2] > 0.0)
			&& (!empty($data[3]) && strlen($data[3]) > 0)
			&& (is_numeric($data[4]) && $data[4] > 0.0)){
			
			$qry = "insert into path_end set ";
			$qry .= "end_path_id='".$pathID."', ";
			$qry .= "end_distance='".$db_conn->real_escape_string($data[0])."', ";
			$qry .= "end_ground_height='".$db_conn->real_escape_string($data[1])."', ";
			$qry .= "end_antenna_height='".$db_conn->real_escape_string($data[2])."', ";
			$qry .= "end_ant_cable_type='".$db_conn->real_escape_string($data[3])."', ";
			$qry .= "end_ant_cable_length='".$db_conn->real_escape_string($data[4])."';";
			$db_conn->query($qry);
			if ($db_conn->errno){
				echo "<p>Failed to insert first path end point record</p>\n";
				echo "<p>Error ".$db_conn->errno."</p>\n";
				echo "<p>Message ".$db_conn->error."</p>\n";
				fclose($fh);
				$db_conn->rollback();
				$db_conn->close();
				return "FAILED TO INSERT FIRST PATH END POINT RECORD";
			}
		} else {
			fclose($fh);
			$db_conn->rollback();
			$db_conn->close();
			return "Invalid first end point data";
		}
	}

// Read in the second path end point record and add it to the database
	$data = fgetcsv($fh);
	if ($data){
		if ((is_numeric($data[0]) && $data[0] > 0.0)
			&& (is_numeric($data[1]))
			&& (is_numeric($data[2]) && $data[2] > 0.0)
			&& (!empty($data[3]) && strlen($data[3]) > 0)
			&& (is_numeric($data[4]) && $data[4] > 0.0)){
			
			$qry = "insert into path_end set ";
			$qry .= "end_path_id='".$pathID."', ";
			$qry .= "end_distance='".$db_conn->real_escape_string($data[0])."', ";
			$qry .= "end_ground_height='".$db_conn->real_escape_string($data[1])."', ";
			$qry .= "end_antenna_height='".$db_conn->real_escape_string($data[2])."', ";
			$qry .= "end_ant_cable_type='".$db_conn->real_escape_string($data[3])."', ";
			$qry .= "end_ant_cable_length='".$db_conn->real_escape_string($data[4])."';";
			$db_conn->query($qry);
			if ($db_conn->errno){
				echo "<p>Failed to insert second path end point record</p>\n";
				echo "<p>Error ".$db_conn->errno."</p>\n";
				echo "<p>Message ".$db_conn->error."</p>\n";
				fclose($fh);
				$db_conn->rollback();
				$db_conn->close();
				return "FAILED TO INSERT SECOND PATH END POINT RECORD";
			}
		} else {
			fclose($fh);
			$db_conn->rollback();
			$db_conn->close();
			return "Invalid second end point data";
		}
	}

// Read and insert all of the path mid points
	while ($data = fgetcsv($fh)){
		if ((is_numeric($data[0]) && $data[0] > 0.0)
			&& (is_numeric($data[1]))
			&& (!empty($data[2]) && strlen($data[2]) > 0)
			&& (is_numeric($data[3]) && $data[3] >= 0.0)
			&& (!empty($data[4]) && strlen($data[4]) > 0)){
		
			$qry = "insert into path_mid set ";
			$qry .= "mid_path_id='".$pathID."', ";
			$qry .= "mid_distance='".$db_conn->real_escape_string($data[0])."', ";
			$qry .= "mid_ground_height='".$db_conn->real_escape_string($data[1])."', ";
			$qry .= "mid_obstruction_height='".$db_conn->real_escape_string($data[3])."', ";
			$qry .= "mid_terrain_type='".$db_conn->real_escape_string($data[2])."', ";
			$qry .= "mid_obstruction_type='".$db_conn->real_escape_string($data[4])."';";
			$db_conn->query($qry);
			if ($db_conn->errno){
				echo "<p>Failed to insert path mid point record</p>\n";
				echo "<p>Error ".$db_conn->errno."</p>\n";
				echo "<p>Message ".$db_conn->error."</p>\n";
				fclose($fh);
				$db_conn->rollback();
				$db_conn->close();
				return "FAILED TO INSERT PATH MID POINT RECORD";
			}
		} else {
			fclose($fh);
			$db_conn->rollback();
			$db_conn->close();
			return "Invalid mid point data";
		}
	}
	fclose($fh);
	$db_conn->commit();
	$db_conn->close();
	return "SAVED";

}
?>
<?php
function moveUploadFile(){
	$newName = "uploads/".$_FILES['pathFile']['name']."-".strftime("%Y%j%H%M%S").".csv";
	if (move_uploaded_file($_FILES['pathFile']['tmp_name'], $newName)){
		return $newName;
	} else {
		return FALSE;
	}
}
?>
