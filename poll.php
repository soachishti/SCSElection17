<?php
require_once("common.php");

$casted = 0;
if (isset($_GET['id'])) {
	$file = $email_db . $_GET['id'];
	if (!file_exists($file)) {
		header("Location: index.php");
		die();
	}

	$casted = intval(file_get_contents($file));
}
else {
	header("Location: index.php");
	die();
}

if (isset($_POST['submit']) && $casted == 0) {
	file_put_contents($file, "1");
	# TODO: Handle multi threading here;
	foreach($_POST['position'] as $position => $candidate) {
		$handle = fopen($result_file, "a");
		fputcsv($handle, [$position, $candidate]); 
		fclose($handle);	
	}
	$casted = 1;
}

function read_csv($file, $name_index, $position_index) {
	$row = 1;
	
	$position_category = [];
	$candidates = [];
	
	if (($handle = fopen($file, "r")) !== FALSE) {
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			if ($row != 1) {
		        $name = $data[$name_index];
		        $positions = explode(",", $data[$position_index]);
		        foreach($positions as $position) {
		        	$position = trim($position);
		        	$position_category[$position][] = $name;
		        }
		        $candidates[] = $name;
		    }
		    $row++;
	    }
	    fclose($handle);
	}
	
	return [
		"candidates" => $candidates, 
		"position_category" => $position_category
	];
}

$result = read_csv($candidates_files, 1, 3);

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
			<p style="text-align: center;">Kindly select your candidates below.</p>
			<hr />
			<?php if ($casted == 1): ?>
				<div class="alert alert-success">
					Thank you! You have casted your vote.
				</div>
			<?php else: ?>
				<form method="post" action="">
					<?php foreach($positions as $position): ?>
						<h3><?php echo $position; ?></h3>
						<?php foreach($result['position_category'][$position] as $candidate): ?>
							<div class="radio">
								<label><input type="radio" name="position[<?php echo $position; ?>]" value="<?php echo $candidate; ?>"><?php echo $candidate; ?></label>
							</div>
						<?php endforeach; ?>
						<hr />	
					<?php endforeach; ?>
					<input  style="text-align: center;" class="btn btn-primary" type="submit" name="submit" value="Save My Selection">
					<br />
					<br />
				</form>
			<?php endif; ?>
		</div>
		<div class="col col-sm-4"></div>
	</div>
</div>

</body>
</html>
