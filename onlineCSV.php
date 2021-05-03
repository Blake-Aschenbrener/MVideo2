<!-- Blake Aschenbrener (bja50) -->
<!-- CSDS 397: Linux Scripting -->
<!-- Major Project Video 2 Code -->

<h1> Online CSV Processor</h1>
<p> Welcome to the online CSV Processor.</p>

<?php
if (isset($_GET["action"])){
	if($_GET["action"] == "Edit CSVs"){
		header("Location: dataEdit.php");
    		exit;
	} else if ($_GET["action"] == "View CSVs") {
        header("Location: viewCSV.php");
    		exit;
    }
    }
?>

<form id="actionForm" action="onlineCSV.php"  method="GET">
<input type="submit" class="button" value="Edit CSVs" name="action" />
<input type="submit" class="button" value="View CSVs" name="action" />
</form>