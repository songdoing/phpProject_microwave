<?php
    require_once("../includes/db.php");
function resetPathData(){
?>
    <content>
        <form method="POST" id="resetPathForm">
		<h2>Select the path data to reset to original </h2>
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
        <div id="resetPaths"></div>
        <a href="../index.php"> Return to Main Main</a>
    </content>
<?php
}
?>