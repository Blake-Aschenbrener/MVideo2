<!-- Blake Aschenbrener (bja50) -->
<!-- CSDS 397: Linux Scripting -->
<!-- Major Project Video 1 Code -->
<!-- Partner: Erik Espinosa -->


<body>

<h1> Online CSV Editor</h1>
<p> Please enter an input CSV document. You will then be able
process this document by performing operations on the columns.
The resulting changes will be output into a new CSV document.</p>
</body>



<?php

    //Credit to Erik Espinosa for creating this way to display files
    //Resetting the array in case a file either got deleted or created.
    $files = glob("*.csv");
    
    //Printing out a list of all the current .csv files in the current directory.
    for($i=0; $i<sizeof($files); $i++) {
        echo "<li>" . $files[$i] . "</li>\n";
    }

  if (empty($_GET["input"])) {
    echo "<form action=dataEdit.php method='GET'>" ;
    echo "<label for='input' >Name of Input File:</label><br>" ;
    echo "<input type='text' id='input' name='input'><br>" ;
    echo "  <input type=submit>" ;
    echo "</form>" ;
  } else {
	copy($_GET["input"], "internalTempFile.txt");
	header("Location: dataEditActions.php");
	exit;
  }
?>

<hr>