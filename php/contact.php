<?php

if (!$_POST) exit;

function isEmail($email)
{
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

if (!defined("PHP_EOL")) define("PHP_EOL", "\r\n");

$name     = $_POST['name'];
// $lname    = $_POST['lname'];
$subject  = $_POST['subject'];
// $company  = $_POST['company'];
$email    = $_POST['email'];
$message = $_POST['message'];

if (trim($name) == '') {
	echo '<div class="error_message">You must enter your name.</div>';
	exit();
} else if (trim($subject) == '') {
	echo '<div class="error_message">Write your proper subject.</div>';
	exit();
} else if (trim($email) == '') {
	echo '<div class="error_message">Please enter a valid email address.</div>';
	exit();
} else if (!isEmail($email)) {
	echo '<div class="error_message">You have entered an invalid e-mail address. Please try again.</div>';
	exit();
}

if (trim($message) == '') {
	echo '<div class="error_message">Please enter your message.</div>';
	exit();
}

$address = "shadhil90@gmail.co.tz";

$e_subject = 'You have been contacted by ' . $name . '.';

$e_body = "You have been contacted by $name. Their additional message is as follows." . PHP_EOL . PHP_EOL;
$e_content = "\"$message\"" . PHP_EOL . PHP_EOL;
$e_reply = "You can contact $name via email, $email";

$msg = wordwrap($e_body . $e_content . $e_reply, 70);

$headers = "From: $email" . PHP_EOL;
$headers .= "Reply-To: $email" . PHP_EOL;
$headers .= "MIME-Version: 1.0" . PHP_EOL;
$headers .= "Content-type: text/plain; charset=utf-8" . PHP_EOL;
$headers .= "Content-Transfer-Encoding: quoted-printable" . PHP_EOL;

if (mail($address, $e_subject, $msg, $headers)) {
	echo "<div id='success_page'>";
	echo "<h3>Email Sent Successfully.</h3>";
	echo "<p>Thank you <strong>$name</strong>, your message has been submitted to us.</p>";
	echo "</div>";
} else {
	echo 'ERROR!';
}
