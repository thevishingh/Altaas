<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  require '../../third-party/PHPMailer/src/Exception.php';
  require '../../third-party/PHPMailer/src/PHPMailer.php';
  require '../../third-party/PHPMailer/src/SMTP.php';

  //honey pot field
	$honeypot = $_POST['firstname'];

	//check if the honeypot field is filled out. If not, send a mail.
	if( ! empty( $honeypot ) ){
      // $response = [
      //     "status" => false,
      //     "message" => 'Invalid email address, message ignored.'
      // ];
      $response = 'Are you a Robot?';
      echo $response;
      exit();
  } else {
      
      //Don't run this unless we're handling a form submission
      if (array_key_exists('email', $_POST)) {

        //SMTP needs accurate times, and the PHP time zone MUST be set
        //This should be done in your php.ini, but this is how to do it if you don't have access to that
        date_default_timezone_set('Etc/UTC');

        //require '../vendor/autoload.php';

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        //Create a new PHPMailer instance
        $mail = new PHPMailer();
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        //Enable SMTP debugging
        //SMTP::DEBUG_OFF = off (for production use)
        //SMTP::DEBUG_CLIENT = client messages
        //SMTP::DEBUG_SERVER = client and server messages
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
        //Set the SMTP port number - likely to be 25, 465 or 587
        $mail->Port = 587;

        //Set the encryption mechanism to use:
        // - SMTPS (implicit TLS on port 465) or
        // - STARTTLS (explicit TLS on port 587)
        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        //Username to use for SMTP authentication
        $mail->Username = 'abhishek.rajput28@gmail.com';
        //Password to use for SMTP authentication
        $mail->Password = 'xdht pjis qdct uvct';
        //Set who the message is to be sent from
        $mail->setFrom('abhishek.rajput28@gmail.com', 'Abhi Last');
        //Set who the message is to be sent to
        $mail->addAddress('abhishek.rajput28@gmail.com', 'A B');

        if ($mail->addReplyTo($_POST['email'], $_POST['name'])) {
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $mobile = htmlspecialchars($_POST['mobile']);
            // $subject = htmlspecialchars($_POST['subject']);
            $message = htmlspecialchars($_POST['message']);

            $mail->Subject = htmlspecialchars($_POST['subject']);
            //Keep it simple - don't use HTML
            $mail->isHTML(true);
            //Build a simple message body
            $mail->Body = "<html>
            <head>
                <title>Contact Form Submission</title>
            </head>
            <body>
                <h2>Contact Form Submission</h2>
                <p><strong>Name:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Mobile:</strong> {$mobile}</p>
                <p><strong>Message:</strong> {$message}</p>
            </body>
            </html>";

            //Send the message, check for errors
            // if (!$mail->send()) {
                //The reason for failing to send will be in $mail->ErrorInfo
                //but it's unsafe to display errors directly to users - process the error, log it on your server.
                // if ($isAjax) {
                //     http_response_code(500);
                // }

                // $response = [
                //     "status" => false,
                //     "message" => 'Sorry, something went wrong. Please try again later.'
                // ];

            //     $response = 'Sorry, something went wrong. Please try again later.';
            // } else {
                // $response = [
                //     "status" => true,
                //     "message" => 'Message sent! Thanks for contacting us.'
                // ];

                $response = 'OK';
            // }
            
        }
        else {
          // $response = [
          //     "status" => false,
          //     "message" => 'Invalid email address, message ignored.'
          // ];
          $response = 'Invalid email address, message ignored.';
        }

        if ($isAjax) {
          // header('Content-type:application/json;charset=utf-8');
          // echo json_encode($response);
          echo $response;
          exit();
        }

        // $mail->addReplyTo($_POST['email'], $_POST['name']);
        //Set who the message is to be sent to
        // $mail->addAddress('abhishek.rajput28@gmail.com', 'A B');
        //Set the subject line
        // $mail->Subject = 'PHPMailer SMTP test';
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        // $mail->msgHTML(file_get_contents('contents.html'), __DIR__);
        //Replace the plain text body with one created manually
        // $mail->AltBody = 'This is a plain-text message body';
        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');

        //SMTP XCLIENT attributes can be passed with setSMTPXclientAttribute method
        //$mail->setSMTPXclientAttribute('LOGIN', 'yourname@example.com');
        //$mail->setSMTPXclientAttribute('ADDR', '10.10.10.10');
        //$mail->setSMTPXclientAttribute('HELO', 'test.example.com');

        //send the message, check for errors
        // if (!$mail->send()) {
        //     echo 'Mailer Error: ' . $mail->ErrorInfo;
        // } else {
        //     echo 'Message sent!';
        // }

      }
  }

?>
