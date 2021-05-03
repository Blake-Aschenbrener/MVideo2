<!-- Blake Aschenbrener (bja50) -->
<!-- CSDS 397: Linux Scripting -->
<!-- Major Project Video 1 Code -->
<!-- Partner: Erik Espinosa -->

<h1> Online CSV Processor</h1>

<?php

    //This secment will pass the file onto the next page
    if (isset($_GET["action"])){
	if($_GET["action"] == "Finish"){
		header("Location: dataEditComplete.php");
    		exit;
	}
    }

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

   
    //This section checks to see if the user added an input to column one. 
    //If so, an action will be taken.
    if (!empty($_GET["firstCol"])){
   	    $firstCol = $_GET["firstCol"];
	    $secondCol = $_GET["secondCol"];
	    $operator = $_GET["operator"];
	    $newColumnName = $_GET["newColumnName"];
	    $outputFile = $inputFile;
	    $firstIndex = array_search($firstCol, $HEADERS);
	    $secondIndex = array_search($secondCol, $HEADERS);
	    $newArray = []; 
	    
	    if ($operator == "*"){
	        	foreach ($CONTEXT[$firstIndex] as $key=>$val){
				array_push($newArray, $val * $CONTEXT[$secondIndex][$key]);
			}
			array_push($HEADERS, $newColumnName);
			array_push($CONTEXT, $newArray);
	    }elseif ($operator == "/"){
	         	foreach ($CONTEXT[$firstIndex] as $key=>$val){
				array_push($newArray, $val / $CONTEXT[$secondIndex][$key]);
			}
			array_push($HEADERS, $newColumnName);
			array_push($CONTEXT, $newArray);

	    }elseif($operator == "+"){
	        	foreach ($CONTEXT[$firstIndex] as $key=>$val){
				array_push($newArray, $val + $CONTEXT[$secondIndex][$key]);
			}
			array_push($HEADERS, $newColumnName);
			array_push($CONTEXT, $newArray);

	    }elseif($operator == "-"){
	        	foreach ($CONTEXT[$firstIndex] as $key=>$val){
				array_push($newArray, $val - $CONTEXT[$secondIndex][$key]);
			}
			array_push($HEADERS, $newColumnName);
			array_push($CONTEXT, $newArray);

	    } elseif($operator == "Swap"){
			$tempData = $CONTEXT[$firstIndex];
			$tempName = $HEADERS[$firstIndex];
			$CONTEXT[$firstIndex] = $CONTEXT[$secondIndex];
			$HEADERS[$firstIndex] = $HEADERS[$secondIndex];
			$CONTEXT[$secondIndex] = $tempData;
			$HEADERS[$secondIndex] = $tempName;
		} elseif($operator == "Rmv"){
			unset($CONTEXT[$firstIndex]);
			$CONTEXT = array_values($CONTEXT);
			unset($HEADERS[$firstIndex]);
			$HEADERS = array_values($HEADERS);
		}

	    //Now that we created the new data, we will overwrite our internal file to save.
	    $out = fopen($outputFile, "w") or die ("Unable to create output file");
	   
	    $cols = count($CONTEXT);
	    $rows = count($CONTEXT[0]);
	    for ($c=0; $c<$cols-1; $c++){
		    fwrite($out, $HEADERS[$c].",");
	    }
            fwrite($out, $HEADERS[($cols-1)]."\n");	   
	    for ($i = 0; $i<$rows; $i++){
		    for ($c=0; $c<$cols-1; $c++){
			    fwrite($out, $CONTEXT[$c][$i].", ");
		    }
		    fwrite($out, $CONTEXT[($cols-1)][$i]."\n"); 
		    
	    }
	    fclose($out);	     
    
    }
    
   

?>

<!-- This section implements jQuery to help add some customization to the input fields -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("#operator").change(function(){
  	if($(this).val() === "Swap"){
    	$("#newColumnName").css("background-color", "#DDDDDD");
        $("#newColumnName").prop("disabled", true);
		$("#secondCol").css("background-color", "#FFFFFF");
        $("#secondCol").prop("disabled", false);

	} else if($(this).val() === "Rmv") {
		$("#newColumnName").css("background-color", "#DDDDDD");
        $("#newColumnName").prop("disabled", true);
		$("#secondCol").css("background-color", "#DDDDDD");
        $("#secondCol").prop("disabled", true);
    } else {
    	$("#newColumnName").css("background-color", "#FFFFFF");
        $("#newColumnName").prop("disabled", false);
		$("#secondCol").css("background-color", "#FFFFFF");
        $("#secondCol").prop("disabled", false);
    }
    
  });
});
</script>



<!-- This section builds the form to get user input. -->
<form id="actionForm" action="dataEditActions.php?entry"  method="GET">
<label for='firstCol'>Select First Column To Edit</label><br> 
<select id='firstCol' name='firstCol'><br> 
<?php
//We must return to php to get the headers to insert into select
foreach($HEADERS as $label):
echo '<option value="'.$label.'">'.$label.'</option> ';
endforeach;
?>

</select> <br>
<label for='secondCol'>Select Second Column To Edit</label><br> 
<select id='secondCol' name='secondCol'><br> 
<?php
foreach($HEADERS as $label):
echo '<option value="'.$label.'">'.$label.'</option> <br>';
endforeach;
?>
</select> <br>
<label for='operator'>Select Operation</label><br> 
<select id='operator' name='operator'>
<option value="*">*</option>
<option value="/">/</option>
<option value="+">+</option>
<option value="-">-</option>
<option value="Swap">Swap</option>
<option value="Rmv">Remove Column</option>
</select><br>
<label for='newColumnName'>Please enter the new name of the column.</label><br> 
<input type='text' id='newColumnName' name='newColumnName'>
<!-- Each button press will set action to a different val. 
finally takes it to the next page. Add continues this script. -->
<input type="submit" class="button" value="Update" name="action" />
<input type="submit" class="button" value="Finish" name="action" />
</form>
<label for='newColumnName'>Preview of Data</label><br> 

<!-- This Table will show a preview of the data up to 10 rows -->
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
	$rows = count ($CONTEXT[0]);

	if ($rows > 10) {$rows = 10;} 
	for ($x = 0; $x <= $rows; $x++) {

	if (!empty($CONTEXT[0][$x])) {
   	echo '<tr>';
   
	 foreach($CONTEXT as $col):
	 echo '<td>'.$col[$x].'</td>';
	 endforeach;
	
	echo '</tr>';
	}
	}

	if ($rows == 10) {
	foreach($CONTEXT as $col):
		echo '<td> ... </td>';
		endforeach;
	}
	?>
 
</table>



</hr>