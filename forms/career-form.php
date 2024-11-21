<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require '../third-party/PHPMailer/src/Exception.php';
    require '../third-party/PHPMailer/src/PHPMailer.php';
    require '../third-party/PHPMailer/src/SMTP.php';
    require_once '../config/config.php';
    require_once '../config/validation.php';

    //honey pot field
	$honeypot = $_POST['firstname'];
    $response = "";
    $errflag = false;

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

            // Validate name
            if (empty($_POST["name"])) {
                $response .= "<br>Name is required";
                $errflag = true;
            } else {
                $name = sanitize_input($_POST["name"]);
                if (!validate_name($name)) {
                    $response .= "<br>Only letters and white space allowed for name";
                    $errflag = true;
                }
            }

            // Validate email
            if (empty($_POST["email"])) {
                $response .= "<br>Email is required";
                $errflag = true;
            } else {
                $email = sanitize_input($_POST["email"]);
                if (!validate_email($email)) {
                    $response .= "<br>Invalid email format";
                    $errflag = true;
                }
            }

            // Validate position
            if (empty($_POST["position"])) {
                $response .= "<br>Position is required";
                $errflag = true;
            } else {
                $position = sanitize_input($_POST["position"]);
                if (!validate_name($position)) {
                    $response .= "<br>Only letters and white space allowed for Position";
                    $errflag = true;
                }
            }
 
            // Validate mobile number
            if (empty($_POST["mobile"])) {
                $response .= "<br>Mobile number is required";
                $errflag = true;
            } else {
                $mobile = sanitize_input($_POST["mobile"]);
                if (!validate_mobile($mobile)) {
                    $response .= "<br>Invalid mobile number format. Must be 10 digits.";
                    $errflag = true;
                }
            }
    
            // Validate additional info
            if (!empty($_POST["addinfo"])) {
                $addinfo = sanitize_input($_POST["addinfo"]);
            }

            // Validate resume file upload
            if (isset($_FILES['resume']) && $_FILES['resume']['error'] == UPLOAD_ERR_OK) {
                $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                $maxSize = 2 * 1024 * 1024; // 2MB
                if (!validate_file($_FILES['resume'], $allowedTypes, $maxSize)) {
                    $response .= "<br>Resume: Invalid file type or size. Only PDF, DOC, and DOCX files are allowed, and size must be less than 2MB.";
                    $errflag = true;
                } 
                // else {
                //     $fileUploadSuccess = true;
                // }
            } else {
                $response .= "<br>Resume is required.";
                $errflag = true;
            }

            // Validate cover letter file upload
            if (isset($_FILES['coverletter']) && $_FILES['coverletter']['error'] == UPLOAD_ERR_OK) {
                $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                $maxSize = 2 * 1024 * 1024; // 2MB
                if (!validate_file($_FILES['coverletter'], $allowedTypes, $maxSize)) {
                    $response .= "<br>Cover Letter: Invalid file type or size. Only PDF, DOC, and DOCX files are allowed, and size must be less than 2MB.";
                    $errflag = true;
                } 
                // else {
                //     $fileUploadSuccess = true;
                // }
            } else {
                $response .= "<br>Cover Letter is required.";
                $errflag = true;
            }

            if($errflag) {
                echo trim($response, "<br>");
                exit();
            }

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
            $mail->Host = $smtpHost;
            //Set the SMTP port number - likely to be 25, 465 or 587
            $mail->Port = $smtpPort;

            //Set the encryption mechanism to use:
            // - SMTPS (implicit TLS on port 465) or
            // - STARTTLS (explicit TLS on port 587)
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = $smtpUser;
            //Password to use for SMTP authentication
            $mail->Password = $smtpPass;
            //Set who the message is to be sent from
            $mail->setFrom('abhishek.rajput28@gmail.com', 'Abhi Last');
            //Set who the message is to be sent to
            $mail->addAddress('abhishek.rajput28@gmail.com', 'A B');

            if ($mail->addReplyTo($email, $name)) {
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