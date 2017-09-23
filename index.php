<?php
require_once("common.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['email']) && $_POST['email']) {
	$email = strtolower($_POST['email']);
	if (in_array($email, $allowed_emails) == true) {
		$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
		try {
			$hash = hash("sha256", $email . $secret_key);
			$file = $email_db . $hash;
			if (file_exists($file) == false) {
				file_put_contents($file, "0");
			}			
			$url = $base_url . 'poll.php?id=' . $hash; 

		    //Server settings
		    $mail->isSMTP();                                      // Set mailer to use SMTP
		    $mail->Host = $smtp_host;  // Specify main and backup SMTP servers
		    $mail->SMTPAuth = true;                               // Enable SMTP authentication
		    $mail->Username = $smtp_email;                 // SMTP username
		    $mail->Password = $smtp_password;                           // SMTP password
		    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		    $mail->Port = 587;                                    // TCP port to connect to

		    //Recipients
		    $mail->setFrom($smtp_email, $smtp_name);
		    $mail->addAddress($email, $email);     // Add a recipient

		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = 'SCS Election - Polling Page Link';
		    $mail->Body    = 'Your link for polling is:<br /><br /><a href="'. $url .'">'. $url .'</a><br /><br />Regards, <br />' . $smtp_name;

		    $mail->send();
		    $sent = true;
		} catch (Exception $e) {
		    $sent = $mail->ErrorInfo;
		}
	}
	else {
		$sent = "Your email is not in voting list.";
	}
}

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
			<?php if ($sent === true): ?>
				<div class="alert alert-success">
					<strong>Success!</strong> Polling link sent to your email.
				</div>
			<?php elseif ($sent !== null): ?>
				<div class="alert alert-warning">
					<strong>Error!</strong> <?php echo $sent; ?>
				</div>
			<?php endif; ?>

			<form method="post" action="index.php">
				<hr />
				<h3>Steps</h3>
				<ol>
					<li>Enter your email address here.</li>
					<li>If voting is allowed for your email then you will receive a link on your email.</li>
					<li>Opening that link will take you to Polling page.</li>
					<li>Select your candidates and submit the form.</li>
				</ol>
				<hr />
				<div class="form-group" >
					<label for="email">Email:</label>
					<input type="email" class="form-control" name="email" placeholder="bob@nu.edu.pk">
				</div>
				<input style="text-align: right;" class="btn btn-primary" type="submit" value="Send me access">
			</form>
		</div>
		<div class="col col-sm-4"></div>
	</div>
</div>

</body>
</html>
