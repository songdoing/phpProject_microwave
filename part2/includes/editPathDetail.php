<?php
/*
Identification : editPathDetail.php(part2)
Author : group19  (Wonjin, Paul)
Purpose : This is for displaying all tables that users selected, using AJAX with responses containing JSON formatted data. 
*/
    header("Content-Type: application/json");

    require_once("../includes/db.php");

    $db_conn = connect_db();
    if (isset($_SERVER['REQUEST_METHOD']) &&  $_SERVER['REQUEST_METHOD'] == "POST"){
        loadPathFromDB(); 
    }

    function loadPathFromDB() {
        if(isset($_POST['choice'])) {
            $choice = $_POST['choice'][0];
            $db_conn = connect_db();
            
            // load from path table
            $qry = "SELECT * FROM path where path_id = ".$choice."; ";
            $rs = $db_conn->query($qry); 
            
            $err_msgs = array();
            
            if ($rs->num_rows > 0){
                $data = array("status" => "OK");
                $data['paths'] = array();
                while ($row = $rs->fetch_assoc()){
                    array_push($data['paths'], $row);
                }
            } else {
                echo '{ "status": "None" }';
            }
            
            // load from path_end table
            $qry = "SELECT * FROM path_end where end_path_id = ".$choice."; ";
            $rs = $db_conn->query($qry);
            $err_msgs = array();
            
            if ($rs->num_rows > 0){
                while ($row = $rs->fetch_assoc()){
                    array_push($data['paths'], $row);
                }
            } else {
                echo '{ "status": "No End Points associated with this Path ID" }';
            }
            
            // load from path_mid table
            $qry = "SELECT * FROM path_mid where mid_path_id = ".$choice."; ";
            $rs = $db_conn->query($qry);
            $err_msgs = array();
            
            if ($rs->num_rows > 0){
                while ($row = $rs->fetch_assoc()){
                    array_push($data['paths'], $row);
                }
            } else {
                echo '{ "status": "No Mid Points associated with this Path ID" }';
            }
            echo json_encode($data);

            $db_conn->close();
        }
    }
?>