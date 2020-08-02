<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
// require_once($_SERVER["DOCUMENT_ROOT"].'/db.class.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/minify.php');
// require_once($_SERVER["DOCUMENT_ROOT"].'/Classes/Class_Geoplugin.php');
// require_once($_SERVER["DOCUMENT_ROOT"].'/Classes/Class_Notifications.php');
define("ISSMTP", 1);
function convert_smart_quotes($string) 
{ 
    $chr_map = array(
        // Windows codepage 1252
        "\xC2\x82" => "'", // U+0082⇒U+201A single low-9 quotation mark
        "\xC2\x84" => '"', // U+0084⇒U+201E double low-9 quotation mark
        "\xC2\x8B" => "'", // U+008B⇒U+2039 single left-pointing angle quotation mark
        "\xC2\x91" => "'", // U+0091⇒U+2018 left single quotation mark
        "\xC2\x92" => "'", // U+0092⇒U+2019 right single quotation mark
        "\xC2\x93" => '"', // U+0093⇒U+201C left double quotation mark
        "\xC2\x94" => '"', // U+0094⇒U+201D right double quotation mark
        "\xC2\x9B" => "'", // U+009B⇒U+203A single right-pointing angle quotation mark
        // Regular Unicode     // U+0022 quotation mark (")
        // U+0027 apostrophe     (')
        "\xC2\xAB"     => '"', // U+00AB left-pointing double angle quotation mark
        "\xC2\xBB"     => '"', // U+00BB right-pointing double angle quotation mark
        "\xE2\x80\x98" => "'", // U+2018 left single quotation mark
        "\xE2\x80\x99" => "'", // U+2019 right single quotation mark
        "\xE2\x80\x9A" => "'", // U+201A single low-9 quotation mark
        "\xE2\x80\x9B" => "'", // U+201B single high-reversed-9 quotation mark
        "\xE2\x80\x9C" => '"', // U+201C left double quotation mark
        "\xE2\x80\x9D" => '"', // U+201D right double quotation mark
        "\xE2\x80\x9E" => '"', // U+201E double low-9 quotation mark
        "\xE2\x80\x9F" => '"', // U+201F double high-reversed-9 quotation mark
        "\xE2\x80\xB9" => "'", // U+2039 single left-pointing angle quotation mark
        "\xE2\x80\xBA" => "'", // U+203A single right-pointing angle quotation mark
    );
    $chr = array_keys  ($chr_map); // but: for efficiency you should
    $rpl = array_values($chr_map); // pre-calculate these two arrays
    return $str = str_replace($chr, $rpl, html_entity_decode($string, ENT_QUOTES, "UTF-8"));
}

function sendForgetMail($to, $subject, $template_name, $message, $headers, $other=array()){
    if(!empty($to)){
        $db = new db();
        if(empty($email_hostname))
        {
            $setting_query2 = $db->prepare("SELECT * FROM `Registration` WHERE id=:id");
            $setting_query2->bindParam(':id', $id, PDO::PARAM_INT);
            $setting_query2->execute();
            $query_data2 = $setting_query2->fetch(PDO::FETCH_ASSOC);
            $email_hostname = 'salmandds7@gmail.com';
            $email_username = $query_data2['Username'];
            $email_password = $query_data2['Passwords'];
        }
        if(ISSMTP == 0) {
            $headers = 'From:$email_hostname' . "\r\n" .'Reply-To:$email_hostname' . "\r\n" .'X-Mailer: PHP/' . phpversion(); 
            $headers .= "MIME-Version: 1.0\r\n"; 
            //$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n"; 
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            foreach($other as $k => $v) {
                $body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            echo "<pre>";print_r($body);echo"</pre>";die;
            if (mail($to,$subject,$body,$headers)) { 
                // echo "1";  die;
            } else { 
                // echo "0"; die;
            } 
        } else if(ISSMTP == 1){
            require_once('phpmailer/PHPMailerAutoload.php');
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            foreach($other as $k => $v) {
                @$body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            $mail->CharSet = 'UTF-8';
            //Tell PHPMailer to use SMTP
            //$mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = 'mail.kamson.com.hk';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            //Whether to use SMTP authentication
            //$mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = 'support@kamson.com.hk';
            //Password to use for SMTP authentication
            $mail->Password = 'f@26!iGNJ0zZ';
            //Set who the message is to be sent from
            $mail->setFrom('support@kamson.com.hk','Kamson');
            //Set an alternative reply-to address
            // $mail->setFrom('test@khalaf.imakeawesomethings.com', 'khalaf');
            //Set who the message is to be sent to
            $mail->addAddress($to, '');
            //Set the subject line
            $mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($body);
            //Replace the plain text body with one created manually
            //$mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
          //  $mail->AddAttachment('https://mysunless.com/assets/userimage/20180913115040.jpg', '20180913115040.jpg');
            //send the message, check for errors
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                // echo "Message sent!";
            }
        }
    }
}
?>