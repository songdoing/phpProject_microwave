<?php
/*
Identification : db.php(part3)
Author : group19  (Wonjin, Paul)
Purpose : This is a file for DB connection.
*/
function connect_db(){
    $db_conn = new mysqli('localhost', 'microwave', '!Microwave2!', 'microwave_paths');
    if ($db_conn->connect_errno) {
        printf ("Could not connect to database server\n Error: "
            .$db_conn->connect_errno ."\n Report: "
            .$db_conn->connect_error."\n");
        die;
    }
	if (!$db_conn->set_charset("utf8")){
		echo "Error, could not set character set\n";
		$db_conn->close();
		die;
	}
    return $db_conn;
}

function disconnect_db($db_conn){
    $db_conn->close();
}

?>

