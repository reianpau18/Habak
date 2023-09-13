<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
$name_field = check_input($_POST["name_field"]);
$mail_field = check_input($_POST["mail_field"]);
$phone_field = check_input($_POST["phone_field"]);
$prod_list = check_input($_POST["prCode_field"]);
$subject_field = check_input($_POST["subject_field"]);
$message_field = check_input($_POST["message_field"]);

try {
        //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'reianthegreat18@gmail.com';                     //SMTP username
    $mail->Password   = 'wmlpwsjzifyxjphc';                               //SMTP password
    $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('reianthegreat18@gmail.com', 'reianpau');
    $mail->addAddress('reianthegreat18@gmail.com', 'reianpau');     //Add a recipient


    //Content
    $mail->isHTML(true);                                  //Set email format to HTML

if ($message_field!=="") {
    $subject = "Callback! From the site -HABAK- was sent an message!";
    $message = file_get_contents('templates/message.html');

    // Fill form
    $message = str_replace('{{ subject }}', $subject_field, $message);
    $message = str_replace('{{ name }}', $name_field, $message);
    $message = str_replace('{{ mail }}', $mail_field, $message);
    $message = str_replace('{{ message }}', $message_field, $message);
} else {
    $form = 'product-form';
    $subject = "Client order! From the site -HABAK- was sent an order!";
    $message = file_get_contents('templates/mail.html');

    // Fill form
    $message = str_replace('{{ name }}', $name_field, $message);
    $message = str_replace('{{ mail }}', $mail_field, $message);
    $message = str_replace('{{ phone }}', $phone_field, $message);

    // Add product list
    $tableRows = '';
    $products = json_decode($prod_list);
    foreach ($products as $index => $product) {
        $odd = $index % 2;

        if ($odd) {
            $background = 'bgcolor="#eeeeee"';
        } else {
            $background = '';
        }

        $item = '<tr '.$background.' align="center" style="border-bottom: 1px solid #eeeeee;">';
        $item .= '<td width="50%" style="border-right: 1px solid #eeeeee;"><p style="line-height: 52px; margin: 0; font-size: 12px; color: #363636;">'.$product->title.'</p></td>';
        $item .= '<td width="50%"><p style="line-height: 52px; margin: 0; font-size: 12px; color: #363636;">'.$product->code.'</p></td>';
        $item .= '</tr>';

        $tableRows .= $item;
    }

    $message = str_replace('{{ prodList }}', $tableRows, $message);
}

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

  
mail($to, $subject, $message, $headers);

function check_input($data, $problem = ""){
    $data = htmlentities(trim(strip_tags(stripslashes($data))), ENT_NOQUOTES, "UTF-8");

    if ($problem && strlen($data) == 0){
        show_error($problem);
    }

    return $data;
};

function show_error($myError) {
    echo $myError;
    exit();
}

$mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}