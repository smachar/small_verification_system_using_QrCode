<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you</title>
</head>
<body>
    <?php
    $mainpage = 'inscription.html';
    $recipient = $_POST['email'];
    if(!isset($recipient)){
        header("Location:$mainpage");
        exit;
    }
    require 'vendor/autoload.php';
    use Endroid\QrCode\QrCode;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    $recipient_base64 = base64_encode($recipient);
    $qrimagepath = "qrimages/$recipient_base64.png";
    function sendmail($recipient, $qrimagepath){
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            //$mail->Host       = 'smtp-mail.outlook.com';
            $mail->Host       = 'smtp.office365.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'thesenderemail';
            $mail->Password   = 'thesenderpassword';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;// Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port       = 587;
            $mail->setFrom('thesenderemail', 'the sender name');
            $mail->addAddress($recipient);
            $mail->addAttachment($qrimagepath, 'yourqrcode.png', ); // Optional name
            $mail->addEmbeddedImage($qrimagepath, 'yourqrcode');
            $mail->isHTML(true);
            $mail->Subject = 'Invitation to watch the CLASSICO together';
            $mail->Body = 'Here is your verification code!<br>
                <i style="color:red;" ><b>Note: </b>: This E-mail is required
                at the entry.</i><br><br>
                <img src="cid:yourqrcode" alt="cant display qrcode">
                <br><br><br><br><small>You\'ve received this email because you registered for the ClassicoUm6p event, <br>
                if you don\'t know anything about this event, please just ignore this email</small>';

            $mail->AltBody = 'Please bring with you the QrCode that you\'ve 
                received within the attachment.';

            $mail->send();
            //echo 'Message has been sent';
            return 1;
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return 0;
        }
    }
    function registerindb($data){
        include 'connexion.php';
        $query = sprintf("INSERT INTO participants(email, hmail) 
        VALUES('%s', '%s')",
        mysqli_real_escape_string($con, $data),
        mysqli_real_escape_string($con, sha1($data)));
        $result = mysqli_query($con, $query);
        if(!$result){
            //printf('Invalid query: '.mysqli_error($con).'\n');
            return 0;
        }
        mysqli_close($con);
        return 1;
    }
    if(!registerindb($recipient)){
        echo '<p>this email may already exist, although we\'ve sent you another verification email.</p>';
        $qrCode = new QrCode($recipient);
        $qrCode->writeFile($qrimagepath);
        if(!sendmail($recipient, $qrimagepath)){
            echo "if you didn\'t receive the email,<br> 
            please go back to the main page and try again with a different one.
            <br><a href=".$mainpage.">click here to try again</a>";
            //header("Location:$mainpage");
            exit;
        }
        echo '<p><br>
            please check your email for the verification code.</p>';
        exit;
    }
    $qrCode = new QrCode($recipient);
    //header('Content-Type: '.$qrCode->getContentType());
    //$qrimagepath =  $qrCode->writeString();
    $qrCode->writeFile($qrimagepath);
    if(!sendmail($recipient, $qrimagepath)){
        echo "an error accurred while sending you the verification email,<br> 
        please wait a little bit and try again,
        <br><a href=".$mainpage.">click here to try again</a>";
        exit;
        //header("Location:$mainpage");
    }
    echo '<p>succesfully registered! <br>
        please check your email for the verification code.</p>';
    ?>
</body>
</html>

