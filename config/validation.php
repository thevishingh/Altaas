<?php
// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Validation functions
function validate_name($name) {
    return preg_match("/^[a-zA-Z-' ]*$/", $name);
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validate_url($url) {
    return filter_var($url, FILTER_VALIDATE_URL);
}

function validate_date($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function validate_number($number) {
    return is_numeric($number);
}

function validate_mobile($mobile) { 
    return preg_match('/^[0-9]{10}$/', $mobile); 
}

function validate_file($file, $allowedTypes, $maxSize) {
    $fileType = mime_content_type($file['tmp_name']);
    $fileSize = $file['size'];
    return in_array($fileType, $allowedTypes) && $fileSize <= $maxSize;
}

/*
// Initialize variables and error messages
$errors = [];
$name = $email = $url = $date = $number = "";
$fileUploadSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty($_POST["name"])) {
        $errors['name'] = "Name is required";
    } else {
        $name = sanitize_input($_POST["name"]);
        if (!validate_name($name)) {
            $errors['name'] = "Only letters and white space allowed";
        }
    }

    // Validate email
    if (empty($_POST["email"])) {
        $errors['email'] = "Email is required";
    } else {
        $email = sanitize_input($_POST["email"]);
        if (!validate_email($email)) {
            $errors['email'] = "Invalid email format";
        }
    }

    // Validate URL
    if (!empty($_POST["url"])) {
        $url = sanitize_input($_POST["url"]);
        if (!validate_url($url)) {
            $errors['url'] = "Invalid URL format";
        }
    }

    // Validate date
    if (!empty($_POST["date"])) {
        $date = sanitize_input($_POST["date"]);
        if (!validate_date($date)) {
            $errors['date'] = "Invalid date format";
        }
    }

    // Validate number
    if (!empty($_POST["number"])) {
        $number = sanitize_input($_POST["number"]);
        if (!validate_number($number)) {
            $errors['number'] = "Invalid number format";
        }
    }

    // Validate file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        if (!validate_file($_FILES['file'], $allowedTypes, $maxSize)) {
            $errors['file'] = "Invalid file type or size. Only PDF, DOC, and DOCX files are allowed, and size must be less than 5MB.";
        } else {
            $fileUploadSuccess = true;
        }
    } else {
        $errors['file'] = "No file uploaded or there was an error uploading the file.";
    }

    // If no errors, process the form
    if (empty($errors)) {
        // Process the data (e.g., save to a database, send an email, etc.)
        if ($fileUploadSuccess) {
            // Move the uploaded file to the desired directory
            $fileDest = 'uploads/' . basename($_FILES['file']['name']);
            move_uploaded_file($_FILES['file']['tmp_name'], $fileDest);
            echo "File uploaded successfully!";
        }
        echo "Form data processed successfully!";
    } else {
        // Display errors
        foreach ($errors as $key => $error) {
            echo $key . ": " . $error . "<br>";
        }
    }
}
*/

?>