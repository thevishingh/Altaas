<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require '../third-party/PHPMailer/src/Exception.php';
    require '../third-party/PHPMailer/src/PHPMailer.php';
    require '../third-party/PHPMailer/src/SMTP.php';

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
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && array_key_exists('email', $_POST)) {

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
                $position = htmlspecialchars($_POST['position']);
                $mobile = htmlspecialchars($_POST['mobile']);
                // $subject = htmlspecialchars($_POST['subject']);
                $addinfo = htmlspecialchars($_POST['addinfo']);

                $mail->Subject = "Career - {$name} applied for {$position}";

                //Keep it simple - don't use HTML
                $mail->isHTML(true);
                //Build a simple message body
                $mail->Body = "<html>
                <head>
                    <title>Career Form Application</title>
                </head>
                <body>
                    <h2>Career Form Submission</h2>
                    <p><strong>Name:</strong> {$name}</p>
                    <p><strong>Email:</strong> {$email}</p>
                    <p><strong>Position:</strong> {$position}</p>
                    <p><strong>Mobile:</strong> {$mobile}</p>";
                    if(!empty($addinfo)):
                     $mail->Body .= "<p><strong>Addition Info:</strong> {$addinfo}</p>";
                    endif;
                    $mail->Body .= "</body>
                </html>";
                
                // Attachments
                if (isset($_FILES['resume']) && $_FILES['resume']['error'] == UPLOAD_ERR_OK) {
                    $mail->addAttachment($_FILES['resume']['tmp_name'], $_FILES['resume']['name']);
                }
                
                // Attachments
                if (isset($_FILES['coverletter']) && $_FILES['coverletter']['error'] == UPLOAD_ERR_OK) {
                    $mail->addAttachment($_FILES['coverletter']['tmp_name'], $_FILES['coverletter']['name']);
                }
                
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
            
        }
    }

?>