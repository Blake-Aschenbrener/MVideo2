<!-- Blake Aschenbrener (bja50) -->
<!-- CSDS 397: Linux Scripting -->
<!-- Major Project Video 2 Code -->

<h1> Online CSV Viewer</h1>



<?PHP

if (!(isset($_GET["entry"]))){
    $pgcount = 10;
} else {
    $pgcount = $_GET["entry"];
}

if (isset($_GET["action"])){
	if($_GET["action"] == "Finish"){
		header("Location: onlineCSV.php");
    		exit;
	} else if ($_GET["action"] == "Scroll Down"){
        $pgcount = $pgcount + 10;
    } else if ($_GET["action"] == "Scroll Up"){
        $pgcount = $pgcount - 10;
    }
    }

echo "Current Entries: ".($pgcount-9)." - ".$pgcount." ";
$inputFile = "internalTempFile.txt";
$myfile = fopen($inputFile, "r") or die("Unable to open file!");
//Create two arrays. HEADERS contains the headers and CONTEXT contains the column information
//CONTEXT is two dimentional. The first dimention maps one to one with heach header. The second is for all points under the header
$row = 1;
while(($data = fgetcsv($myfile, 1000, ","))!== FALSE) {
    if ($row == 1){
        $HEADERS= $data;
        $CONTEXT= [];
        for ($i=0; $i<count($data); $i++){
            $CONTEXT[$i] = array();
        }
    }else {
   for ($i=0; $i<count($data); $i++){
            array_push($CONTEXT[$i], $data[$i]);
        }
   
        }
    $row += 1;
}

fclose($myfile);

?>


<!-- This Table will show a preview of the data up to 10 rows -->
<form id="actionForm" action="viewOpenCSV.php"  method="GET">;
<style>
table, th, td {
  border: 1px solid black;
}
</style>
<table style="width:100%">
  <tr>
  		<?php
			foreach($HEADERS as $label):
			echo '<th>'.$label.'</th>';
			endforeach;
		?>	
  </tr>
  <?php

    $initial = $pgcount - 10;
	for ($x = $initial; $x < $pgcount; $x++) {
        if (!empty($CONTEXT[0][$x])) {
        echo '<tr>';
    
	 foreach($CONTEXT as $col):
	 echo '<td>'.$col[$x].'</td>';
	 endforeach;
	
	echo '</tr>';
	}
}

	foreach($CONTEXT as $col):
		echo '<td> ... </td>';
		endforeach;
	?>

</table>

<input type="submit" class="button" value="Scroll Down" name="action" id="Scroll Down"/>
<input type="submit" class="button" value="Scroll Up" name="action" id="Scroll Up"/>
<input type="submit" class="button" value="Finish" name="action" id="Finish"/>

<?php
echo '<input type="hidden" name="entry" value="'.$pgcount.'"/>';
?>

</form>
</head>