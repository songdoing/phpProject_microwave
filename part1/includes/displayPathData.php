<?php
	require_once("../includes/db.php");

    function displayPathData() {
        $db_conn = connect_db();
        $qry = "SELECT * FROM path ORDER BY path_name ASC";
        $rs = $db_conn->query($qry);
?>
    <div id="wrapper" class="container">
        <div id="pathList" class="container">
            <form method="POST" id="displayPathForm">
                <h2>Choose the path to display.</h2>
                <table border="1">
                    <tr><th>Choice</th><th>Path Name</th></tr>
<?php
                    while($row=mysqli_fetch_array($rs)) {
?>
                        <tr>
                            <td width="10%"><input type="radio" name="choice[]" value="<?php echo $row['path_id']; ?>" ><input type="submit" name="displayBtn" value="Choice"/></td>
                            <td width="10%"><?php echo $row['path_name']; ?></td>
                        </tr>
<?php                    
                    }
?>
                </table>
            </form>
            </div>

            <div id="general"></div>
            <div id="end"></div>
            <div id="mid"></div>
            <a href="../index.php"> Return to Main </a>
    </div>
<?php    
    }
?>