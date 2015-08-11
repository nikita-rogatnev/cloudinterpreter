<?php


$task = $_REQUEST['task'];

switch($task) {
    case 'queueEmail':
        queueEmail();
    break;
	case 'emailForFreeMinutes':
		emailForFreeMinutes();
	break;
}

function emailForFreeMinutes() {
	

	$con=mysqli_connect("localhost","kosmos","FUyA9Efu","landingPage");
	
	$email = $_REQUEST['email'];
	
	if (mysqli_connect_errno($con)) {
	      echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

    $sql = "INSERT INTO `jos_users` (`id`, `email`) VALUES (NULL, '".$email."')";

	    if (!mysqli_query($con,$sql))
	      {
	      die('Error: ' . mysqli_error($con));
	      }
	    echo "Спасибо";
	    mysqli_close($con);
}

function queueEmail() {

    $email = $_REQUEST['email'];


    $to      = 'support@cloudinterpreter.com';
    $subject = 'запрос на перевод';
    $message = 'запрос на перевод от '.$email.' в '.date('l jS \of F Y h:i:s A');
    $headers = 'From: admin@cloudinterpreter.com' . "\r\n" .
        //'Reply-To: webmaster@example.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);

}