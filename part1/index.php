<?php
/*
Identification : index.php
Author : group19  (Wonjin, Paul)
Purpose : This is the main index page for Part 1 of the project.
*/
	require_once("./includes/loadPathData.php");
	require_once("./includes/displayPathData.php");
	require_once("./includes/resetPathData.php");
	//require_once("./includes/displayPathDetail.php");
?>
<html>
<head>
	<title>INFO-5094 Project - HOME</title>
	
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900" rel="stylesheet" />
	<link href="../css/default.css" rel="stylesheet" type="text/css" media="all" />
	<link href="../css/fonts.css" rel="stylesheet" type="text/css" media="all" />    
	<script src="../js/jquery-3.3.1.min.js" type="text/javascript"></script>
    <script src="js/ajax.js"></script> 

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
		if (isset($_GET['action']) && $_GET['action'] == "load"){
			loadPathForm();
		} else if (isset($_GET['action']) && $_GET['action'] == "display"){
            displayPathData();
        } else if (isset($_GET['action']) && $_GET['action'] == "reset"){
            resetPathData();
        } else {
			displayInvalidRequest();
		}
	} else if (isset($_SERVER['REQUEST_METHOD']) &&  $_SERVER['REQUEST_METHOD'] == "POST"){
		if (isset($_POST['uploadPathFile']) && $_POST['uploadPathFile'] == "Upload"){
			$err_msgs = validatePathFile();
			if (count($err_msgs)){
				displayErrors($err_msgs);
				loadPathForm();
			} else {
				$status = savePathData();
				displayStatus($status);
			}
		}
		
	} else {
		displayInvalidRequest();
	}

?>
</body>
</html>
<?php
function displayInvalidRequest(){
	echo "<h2> Invalid Request, please return to the main menu and make another selection</h2>\n";
	echo "<a href=\"../index.php\"> Return to Main Main</a>\n";
}
function displayStatus($status){
	echo "<h2> Path information ".$status." </h2>\n";
	echo "<a href=\"../index.php\"> Return to Main Main</a>\n";
}
function displayErrors($err){
	echo "<h3> The following errors were found</h3>\n";
	foreach ($err as $e){
		echo "<p>".$e."</p>\n";
	}
	echo "\n";
}
?>
