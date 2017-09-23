<?php
require_once("common.php");

function process_result_csv($file, $position_index, $name_index) {
	$row = 1;
	
	$position_category = [];
	$candidates = [];
	
	if (($handle = fopen($file, "r")) !== FALSE) {
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		    $name = $data[$name_index];
		    $position = $data[$position_index];
	        
		    if (isset($result[$position][$name]))
	        {
	        	$result[$position][$name]++;
	        }
	        else {
	        	$result[$position][$name] = 1;
	        }
	    }
	    fclose($handle);
	}
	
	return $result;
}

$result = process_result_csv($result_file, 0, 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Software Competition Society Election - 2017</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
	<div class="row">
		<div class="col col-sm-4"></div>
		<div class="col col-sm-4">
			

			<h2 style="text-align: center;">Software Competition Society Election - 2017</h2>
			<br /><br />
			<?php foreach($positions as $position): ?>
				<h3><?php echo $position; ?></h3>
				<table class="table">
					<tr>
						<th>Name</th>
						<th style="text-align: right;">Votes</th>
					</tr>
					<?php 
					arsort($result[$position]);
					foreach($result[$position] as $candidate => $vote): ?>
					<tr>
						<td><?php echo $candidate; ?></td> 
						<td style="text-align: right;"><?php echo $vote; ?></td>
					</tr> 
					<?php endforeach; ?>
				</table>
				<hr />	
			<?php endforeach; ?>
			
		</div>
		<div class="col col-sm-4"></div>
	</div>
</div>

</body>
</html>
