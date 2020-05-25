<?php
/*
Identification : calculate.php(part3)
Author : group19  (Wonjin, Paul)
Purpose :  an AJAX request is to be sent to the web server for calculations to be performed.
*/
header("Content-Type: application/json");

require_once("../includes/db.php");

$db_conn = connect_db();
if (isset($_SERVER['REQUEST_METHOD']) &&  $_SERVER['REQUEST_METHOD'] == "POST"){
    displayCalculation(); 

}
function displayCalculation() {
    $heightAdjust = array();
    $F1 = array();
    $appGroundHeight = array();
    $totalAppHeight = array();
    $data= array();
    $factor= "";
    $choice= "";
    $freGHz= 0;
    $length= 0;
    $pathAttenuation= "";
    $disToMid= 0.0;
    $curvature= "";
    $curveEffect=0;
    $firstFreznel=0;
    $apparentGroundHeight=0;
    $totalApparentHeight=0;
    $axisX = array();
    $antennaeHeightArr = array();
    $keys=array();

    if(isset($_POST['choice'])) {
        $choice = $_POST['choice'][0];


        $factor = $_POST['curveSelect'];
        
     //   echo json_encode($_POST);

        header("Content-Type: application/json");
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
                $freGHz = $row['path_frequency'];
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
                $antennaeHeight = $row['end_ground_height'] + $row['end_antenna_height'];
                $row['ah'] = $antennaeHeight;
                array_push($data['paths'], $row); 
                array_push($antennaeHeightArr, round($antennaeHeight,2));              
            }
            $keys = array(round($data['paths'][1]['end_distance'],2), round($data['paths'][2]['end_distance'],2));
            $antennaeHeightCom = array_combine($keys, $antennaeHeightArr);
            $data['antennaeHeightCom'] = $antennaeHeightCom;

            foreach($antennaeHeightCom as $key => $value) {

                $antennaeHeightLine[] = array("x" => floatval($key), "y" => floatval($value));

            }
            $data['antennaeHeightArr'] = $antennaeHeightLine;

        } else {
            echo '{ "status": "No End Points associated with this Path ID" }';
        }
        
        //calculate Path Attenuation
        $length = $data['paths'][2]['end_distance'];
        $pathAttenuation = 92.4 + (20*log($freGHz, 10)) + (20*log($length, 10));
        $data['pathAttenuation'] = round($pathAttenuation,1); 


        // load from path_mid table
        $qry = "SELECT * FROM path_mid WHERE mid_path_id = ".$choice."; ";
        $rs = $db_conn->query($qry);
        $err_msgs = array();

        if($rs->num_rows > 0) {

            while ($row = $rs -> fetch_assoc()) {

                //calculate curveEffect(h), firstFreznel(F1), ApparentGroundHeight and TotalApparentHeigh value for each curvature factor
                $disToMid = $row['mid_distance'];
                
                if($factor == '4/3') {
                    $curvature = 17;
                }else if($factor == '1') {
                    $curvature = 12.75;
                }else if($factor == '2/3') {
                    $curvature = 8.5;
                }else if($factor == 'infinity') {
                    $curvature = 0;
                }
                $data['factor'] = $factor;
                //calculate curveEffect value
                if($curvature == 0) {
                    $curveEffect = 0;
                }else {
                    $curveEffect = ($disToMid*($length-$disToMid))/$curvature;
                }

                //calculate firstFreznel value
                $firstFreznel = ($disToMid*($length-$disToMid))/($freGHz*$length);
                $firstFreznel = 17.3*sqrt($firstFreznel);

                //calculate ApparentGroundHeight and TotalApparentHeight value
                $apparentGroundHeight = $row['mid_ground_height'] + $row['mid_obstruction_height'] + $curveEffect;
                $totalApparentHeight = $apparentGroundHeight + $firstFreznel;

                $row['curvature'] = round($curveEffect, 4);
                $row['first_freznel'] = round($firstFreznel, 4);
                $row['agh'] = round($apparentGroundHeight, 4);
                $row['total_clearance'] = round($totalApparentHeight, 4);

                array_push($data['paths'], $row);

                array_push($heightAdjust, round($curveEffect, 4));
                array_push($F1, round($firstFreznel, 4));
                array_push($appGroundHeight, round($apparentGroundHeight, 4));
                array_push($totalAppHeight, round($totalApparentHeight, 4));
                array_push($axisX, round($disToMid, 2));
                
            } //end while
            //combine axisX array with appGroudHeight array so that make associative array
            $appGroundHeightCom = array_combine($axisX, $appGroundHeight);
            $appGroundHeightCom = array($data['paths'][1]['end_distance'] => $data['paths'][3]['mid_ground_height']) + $appGroundHeightCom;
            $appGroundHeightCom = $appGroundHeightCom + array($data['paths'][2]['end_distance'] => $data['paths'][16]['mid_ground_height']);
                    
            $data['appGroundHeightCom'] = $appGroundHeightCom;

            //combine axisX array with totalAppHeight array so that make associative array
            $totalAppHeightCom = array_combine($axisX, $totalAppHeight);
            $totalAppHeightCom = array($data['paths'][1]['end_distance'] => $data['paths'][3]['mid_ground_height']) + $totalAppHeightCom;
            $totalAppHeightCom = $totalAppHeightCom + array($data['paths'][2]['end_distance'] => $data['paths'][16]['mid_ground_height']);
            
            $data['totalAppHeightCom'] = $totalAppHeightCom;
            
            //assign axisX and axisY for appGroundHeight
            foreach($appGroundHeightCom as $key => $value) {

                $appGroundHeightLine[] = array("x" => floatval($key), "y" => floatval($value));

            }
            //assign axisX and axisY for totalAppHeight
            foreach($totalAppHeightCom as $key => $value) {

                $totalAppHeightLine[] = array("x" => floatval($key), "y" => floatval($value));

            }
		
            $data['heightAdjust'] = $heightAdjust;
            $data['F1'] = $F1;
            $data['appGroundHeight'] = $appGroundHeightLine;
            $data['totalAppHeight'] = $totalAppHeightLine;
            
            
        }else {
                echo '{ "status": "No Mid Points associated with this Path ID" }';
        } 
    }    
    echo json_encode($data);
    
    $db_conn->close();
}



?>
