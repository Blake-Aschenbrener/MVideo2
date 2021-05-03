<!-- Blake Aschenbrener (bja50) -->
<!-- CSDS 397: Linux Scripting -->
<!-- Major Project Video 2 Code -->



<?php

  if (empty($_GET["output"])) {
    echo "<form action=dataEditComplete.php method='GET'>" ;
    echo "<label for='output'>Name of Output File:</label><br>" ;
    echo "<input type='text' id='output' name='output'>" ;
    echo "  <input type=submit>" ;
    echo "</form>" ;
  } else {
	copy("internalTempFile.txt", $_GET["output"]);
	header("Location: onlineCSV.php");
	exit;
  }
?>

<hr>