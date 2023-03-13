<?php
   
function sendBill($recipientName, $recipientMail, $attachment) {

        require  __DIR__ . '../../vendor/autoload.php';

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;

        $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;            //Enable verbose debug output
        $mail->isSMTP();                                  //Send using SMTP
        $mail->Host       = $GLOBALS['mailHost'];         //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                         //Enable SMTP authentication
        $mail->Username   = $GLOBALS['smtpUser'];         //SMTP username
        $mail->Password   = $GLOBALS['smtpPw'];           //SMTP password
        $mail->SMTPSecure = 'STARTTLS';                   //Enable implicit TLS encryption
        $mail->Port       = $GLOBALS['smtpPort'] ;        //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($GLOBALS['sentFrom'], 'Madkassen');
        $mail->addAddress( $recipientMail, $recipientName);

        $mail->addAttachment($attachment);   

        //Content
        $mail->isHTML(true);                                
        $mail->Subject = 'Madregning';
        $mail->Body    = '<h3>Hej ' . $recipientName . '!</h3><br><br><h5>Din madregning er vedhæftet.</h5><br><br><h5>Med venlig hilsen fra Madkassen</h5>';
        $mail->AltBody = 'Hej ' . $recipientName . '!Din madregning er vedhæftet. Med venlig hilsen fra Madkassen';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>