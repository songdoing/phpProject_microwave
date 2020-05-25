<?php
/*
Identification : index.php(part3)
Author : group19  (Wonjin, Paul)
Purpose : This is the main index page for Part 3 of the project.
*/

require_once("../includes/db.php");

$db_conn = connect_db();
?>
<html>
<head>
	<title>INFO-5094 Project - HOME</title>
<!--      <link rel="stylesheet" type="text/css" href="./css/main.css" >    -->
	<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900" rel="stylesheet" />
	<link href="../css/default.css" rel="stylesheet" type="text/css" media="all" />
	<link href="../css/fonts.css" rel="stylesheet" type="text/css" media="all" />
 
	<script src="../js/jquery-3.3.1.min.js" type="text/javascript"></script>
    <script src="js/ajax.js"></script>
	<script src="js/canvasjs.min.js"></script> 	
</head>
<body>
	<div id="header-wrapper" class="container">
        <div id="header" >
            <div id="menu">
                <ul>
				<li class="current_page_item"><a href="../index.php" class="icon icon-home"> HOME</a></li>
                </ul>
            </div>
            <div id="logo">
			<h1>Microwave Paths</h1>
                <span>By Wonjin Song and Paul Taylor, LAMP2 - GROUP 19</span>
            </div>
        </div>
    </div>

<?php 
if (isset($_SERVER['REQUEST_METHOD']) &&  $_SERVER['REQUEST_METHOD'] == "GET"){
	if (isset($_GET['action']) && $_GET['action'] == "calculate"){
		calculateForm();
	}  else {
		displayInvalidRequest();
	}
} else {
	displayInvalidRequest();
}

?>
</body>
</html>
<?php
function calculateForm() {
	$db_conn = connect_db();
	$qry = "SELECT * FROM path ORDER BY path_name ASC";
	$rs = $db_conn->query($qry);
?>
    <div id="wrapper" class="container">
		<div id="pathList" class="container">
			<form method="POST" id="calculateForm">
				<h2>Select Path and Curvature factors to calculate.</h2>
				<table border="1">
					<tr><th>Select</th><th>Path Name</th></tr>
<?php
					while($row=mysqli_fetch_array($rs)) {
?>
						<tr>
							<td width="10%"><input type="radio" name="choice[]" value="<?php echo $row['path_id']; ?>" ></td>
							<td width="10%"><?php echo $row['path_name']; ?></td>
						</tr>
<?php
					}
?>
                    <tr><th>Select</th><th>Curvature factor</th></tr>
                    <tr><td width="10%"><input type="radio" name="curveSelect" id="curveSelect" value="4/3" ></td><td>4/3</td></tr>
                    <tr><td width="10%"><input type="radio" name="curveSelect" id="curveSelect" value="1" ></td><td>1</td></tr>
                    <tr><td width="10%"><input type="radio" name="curveSelect" id="curveSelect" value="2/3" ></td><td>2/3</td></tr>
                    <tr><td width="10%"><input type="radio" name="curveSelect" id="curveSelect" value="infinity" ></td><td>Infinity</td></tr>
                </table>

                <input type="submit" id="calculate" for="calculateForm" name="calculate" value="Calculate" />
			</form>
		</div>
        <div id="pathAttenuation" style="display: none">
			<h1>Calculation Results</h1>
			<h1>Path Attenuation(dB) : <span id="paValue"></span> </h1>
		</div>
        <div id="graphContainer" style="display: none; height:500px; width:1000px; margin:0 auto;">

		</div>

		<!-- draw blank tables for all 3 sections --> 
		<div id="general" style="display: none">
		
			<!-- draw blank table for general section --> 
			<h3>General Information</h3>
			<div id="genMessages"></div>

			<FORM method="POST" id="updateGform">
				<input type="hidden" id="path_id"/>
				<table id="table1" border="1">
					<tr>
						<th>Path Name</th>
						<th>Operating Frequency</th>
						<th>Description</th>
						<th>Note</th>
					</tr>
					<tr>
						<td id='path_name' width='10%'></td>
						<td id='path_frequency' width='10%' ></td>
						<td id='path_description' width='15%' ></td>
						<td id='path_note' width='15%' ></td>
					</tr>
				</table>
			</FORM>
		</div>

		<!-- draw blank table for end point section --> 
<?php
		$qry = "SELECT * FROM path_end";
		$rs = $db_conn->query($qry);
?>		
		<div id="end" style="display: none">
			<h3>End Points</h3>
			<div id="endMessages"></div>

			<FORM method="POST" id="updateEform">
				<input type="hidden" id="end_id"/>
				<table id="table2" border="1">
				<tr>
					<th>Distance from the start of the path</th>
					<th>Ground height</th>
					<th>Antenna Height</th>
					<th>Cable Type</th>
					<th>Cable Length</th>
				</tr>

<?php
				while($row=mysqli_fetch_array($rs)) {
?>
					<tr id="end_row_<?php echo $row['end_id']; ?>" class="dataRow" style="display: none" >
					<td width="10%" id="end_distance_<?php echo $row['end_id']; ?>" name="end_distance_<?php echo $row['end_id']; ?>"></td>
					<td width="10%" id="end_ground_height_<?php echo $row['end_id']; ?>"></td>
					<td width="10%" id="end_antenna_height_<?php echo $row['end_id']; ?>"></td>
					<td width="10%" id="end_ant_cable_type_<?php echo $row['end_id']; ?>"></td>
					<td width="10%" id="end_ant_cable_length_<?php echo $row['end_id']; ?>"></td>
					</tr>
<?php
}
?>
				</table>
			</FORM>
		</div>
		<!-- draw blank table for mid point section --> 
<?php
		$qry = "SELECT * FROM path_mid";
		$rs = $db_conn->query($qry);
?>	
		<div id="mid" style="display: none">
			<h3>Mid Points</h3>
			<div id="midMessages"></div>

			<FORM method="POST" id="updateMform">
				<input type="hidden" id="mid_id"/>
				<table id="table3"  border="1">
				<tr>
					<th>Distance from Start Point</th>
					<th>Ground height</th>
					<th>Terrain Type</th>
					<th>Obstruction Height</th>
					<th>Obstruction Type</th>
					<th>Curvature Height</th>
					<th>Apparent Ground and Obstruction Height</th>
					<th>1st Freznel Zone</th>
					<th>Total Clearance Height</th>
				</tr>

<?php
				while($row=mysqli_fetch_array($rs)) {
?>
					<tr id="mid_row_<?php echo $row['mid_id']; ?>" class="dataRow" style="display: none">
					<td width="9%" id="mid_distance_<?php echo $row['mid_id']; ?>" name="mid_distance_<?php echo $row['mid_id']; ?>"></td>
					<td width="9%" id="mid_ground_height_<?php echo $row['mid_id']; ?>"></td>
					<td width="9%" id="mid_terrain_type_<?php echo $row['mid_id']; ?>"></td>
					<td width="9%" id="mid_obstruction_height_<?php echo $row['mid_id']; ?>"></td>
					<td width="9%" id="mid_obstruction_type_<?php echo $row['mid_id']; ?>"></td>
					<td width="9%" id="mid_curvature_<?php echo $row['mid_id']; ?>"></td>
					<td width="9%" id="mid_agh_<?php echo $row['mid_id']; ?>"></td>
					<td width="9%" id="mid_first_freznel_<?php echo $row['mid_id']; ?>"></td>
					<td width="9%" id="mid_total_clearance_<?php echo $row['mid_id']; ?>"></td>
					</tr>
<?php
}
?>
				</table>
       
    </div>
<?php  
		
// end of calculateForm
}
	
function displayInvalidRequest(){
	echo "<h2> Invalid Request, please return to the main menu and make another selection</h2>\n";
	echo "<a href=\"../index.php\"> Return to Main</a>\n";
}
function displayStatus($status){
	echo "<h2> Path information ".$status." </h2>\n";
	echo "<a href=\"../index.php\"> Return to Main</a>\n";
}
function displayErrors($err){
	echo "<h3> The following errors were found</h3>\n";
	foreach ($err as $e){
		echo "<p>".$e."</p>\n";
	}
	echo "\n";
	echo "<a href=\"../index.php\"> Return to Main</a>\n";

}
?>