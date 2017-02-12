<?php
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
$secretKey	 = '';
$receiver	 = 'youremail@yourdomain.com';
$ip		 = $_SERVER['REMOTE_ADDR'];
$error		 = array();

$name	 = (isset( $_POST['name'] )) ? $_POST['name'] : '';
$email	 = (isset( $_POST['email'] )) ? $_POST['email'] : '';
$subject = (isset( $_POST['subject'] )) ? $_POST['subject'] : '';
$phone	 = (isset( $_POST['phone'] )) ? $_POST['phone'] : '';
$message = (isset( $_POST['msg'] )) ? $_POST['msg'] : '';
$captcha = (isset( $_POST['g-recaptcha-response'] )) ? $_POST['g-recaptcha-response'] : '';

if ( empty( $name ) ) {
    $error[] = '<div class="alert warning">Please Enter Your Name</div>';
}

if ( empty( $email ) ) {
    $error[] = '<div class="alert warning">Please Enter Your Email ID</div>';
}

if ( !empty( $email ) && !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
    $error[] = '<div class="alert warning">Please Enter Valid Email ID</div>';
}

if ( empty( $subject ) ) {
    $error[] = '<div class="alert warning">Please Enter Your Subject</div>';
}

if ( empty( $phone ) ) {
    $error[] = '<div class="alert warning">Please Enter Your Contact Number</div>';
}

if ( !ctype_digit( $phone ) ) {
    $error[] = '<div class="alert warning">Please Enter Valid Contact Number</div>';
}

if ( empty( $message ) ) {
    $error[] = '<div class="alert warning">Please Enter Your Message</div>';
}

$response		 = file_get_contents( "https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $captcha . "&remoteip=" . $ip );
$decoded_response	 = json_decode( $response, true );

if ( $decoded_response['success'] === false ) {
    $error[] = '<div class="alert warning">Captcha Not Varified</div>';
}

if ( empty( $error ) ) {
    $headers = 'From: Contact Request From (' . $email . ')' . PHP_EOL
	. 'Reply-To: ' . $email . PHP_EOL
	. 'X-Mailer: PHP/' . phpversion();
    $body	 = "Name: {$name}" . PHP_EOL;
    $body .= "Email: {$email}" . PHP_EOL;
    $body .= "Subject: {$subject}" . PHP_EOL;
    $body .= "Phone: {$phone}" . PHP_EOL;
    $body .= "Message: {$message}" . PHP_EOL;

    mail( $receiver, $subject, $body, $headers );
    echo json_encode( array( 'mail' => true, 'msg' => '<div class="alert success">Email is sent</div>' ) );
} else {
    echo json_encode( array( 'mail' => false, 'msg' => $error ) );
}
exit;
