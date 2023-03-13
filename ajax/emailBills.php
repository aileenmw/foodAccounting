<?php
   
    include '../functions.php';  
    include '../../conf/madregnskab/globals.php';
    require_once  __DIR__ ."../../vendor/autoload.php";
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    $folder = $_POST['folder'] ?? "tenantPdfs/11-03-2023/";

    $tenantsPath = "../xml/tenants.xml"; 
    $tenants = new SimpleXMLElement( $tenantsPath, false, true);   

    if ($folder) {
        $folder = "../" . $folder;
        $files = scandir($folder);
        $files = array_diff($files, array('..', '.'));

        $sentTo = [];
        $emailMissing = [];
        $er = [];

        foreach($files as $file) {       

            $houseArr = explode("_", $file);
            $billHouse = $houseArr[0]; 

            foreach ($tenants as $tenant) {
                if ($tenant['Nr'] == $billHouse) {
                    if($tenant->Email == "") {
                        $emailMissing[] = $tenant->Fornavn;
                    } else {
                        // TODO skal afkommenteres
                        if($tenant->Email == "aileenmw@gmail.com") {
                            $mail = new PHPMailer(true);  
                            $attachment = $folder . $file;
                        try {
                            //Server settings
                            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;            //Enable verbose debug output
                            $mail->isSMTP();                                  //Send using SMTP
                            $mail->Host       = $GLOBALS['mailHost'];         //Set the SMTP server to send through
                            $mail->SMTPAuth   = true;                         //Enable SMTP authentication
                            $mail->Username   = $GLOBALS['smtpUser'];         //SMTP username
                            $mail->Password   = $GLOBALS['smtpPw'];           //SMTP password
                            $mail->SMTPSecure = 'STARTTLS';                   //Enable implicit TLS encryption
                            $mail->Port       = $GLOBALS['smtpPort'] ;        //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                            //Recipients
                            $mail->setFrom($GLOBALS['sentFrom'], 'Madkassen');
                            $mail->addAddress( $tenant->Email,  $tenant->Fornavn);

                            $mail->addAttachment($attachment);   

                            //Content
                            $mail->isHTML(true);                                
                            $mail->Subject = 'Madregning - TEST!!!';
                            // $mail->Body    = '<h3>Hej ' . $tenant->Fornavn . '!</h3><h3>Din madregning er vedhæftet.</h3><h3>Med venlig hilsen fra Madkassen</h3>';
                            // $mail->AltBody = 'Hej ' . $tenant->Fornavn . '!Din madregning er vedhæftet. Med venlig hilsen fra Madkassen';
                            $mail->Body    = '<h3>Hej ' . $tenant->Fornavn . '!</h3><h3>Hvis du ved en fejl har fået denne mail så se bort fra den.<br>Den vedhæftede fil har ikke noget at gøre med jeres rigtige regning!<br>Det er bare en test af vores nye system</h3><h3>Med venlig hilsen fra Madkassen</h3>';
                            $mail->AltBody = 'Hej ' . $tenant->Fornavn . '! Hvis du ved en fejl har fået denne mail så se bort fra den. Den vedhæftede fil har ikke noget at gøre med jeres rigtige regning! Det er bare en test af vores nye system';

                            if($mail->send()) {
                                $sentTo[] = $tenant->Email;                            
                            }
                        } catch (Exception $e) {
                            $er[] = "Message could not be sent to: " . $tenant->Email . ", Mailer Error: {$mail->ErrorInfo}";
                        }
                         // TODO skal afkommenteres
                        }
                    }
                }   
            }
        }
    } else {
        $er[] = "Postdata is missing";
    }

    $res["sentTo"] = $sentTo;
    $res["noEmail"] = $emailMissing;
    $res["er"] = $er;

    echo json_encode($res);
?>