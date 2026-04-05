<?php
	$name = isset($_POST['name']) ? trim($_POST['name']) : '';
	$email = isset($_POST['email']) ? trim($_POST['email']) : '';
	$message = isset($_POST['message']) ? trim($_POST['message']) : '';
	$contact_area = isset($_POST['contact_area']) ? strtolower(trim($_POST['contact_area'])) : 'apps';

	if ($name === '' || $email === '' || $message === '')
	{
		$error = array("message" => "Missing required form fields.");
		header('Content-Type: application/json');
		http_response_code(400);
		echo json_encode($error);
		return false;
	}

	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		$error = array("message" => "Invalid email address.");
		header('Content-Type: application/json');
		http_response_code(400);
		echo json_encode($error);
		return false;
	}

	$is_games_contact = ($contact_area === 'games');

	// Route support by area: Centum/Games -> MichGames Studio, Apps -> Camaco Apps.
	$to = $is_games_contact ? 'michgamesstudio@gmail.com' : 'camacoapps@gmail.com';
	$email_subject = $is_games_contact ? "MichGames Contact Form" : "Camaco Apps Contact Form";
	$email_body = "You have received a new message. \n\nArea: " . strtoupper($contact_area) . " \nName: $name \nEmail: $email \nMessage: $message \n";
	$headers = "MIME-Version: 1.0\r\nContent-type: text/plain; charset=UTF-8\r\n";
	$headers .= "From: contact@yoursite.com\r\n";
	$headers .= "Reply-To: $email";

	// Post Message
	if (function_exists('mail'))
	{
		$result = mail($to, $email_subject, $email_body, $headers);
	}
	else // Mail() Disabled
	{
		$error = array("message" => "The php mail() function is not available on this server.");
		header('Content-Type: application/json');
		http_response_code(500);
		echo json_encode($error);
	}
?>
