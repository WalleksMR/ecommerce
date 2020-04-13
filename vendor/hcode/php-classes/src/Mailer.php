<?php 
    namespace hcode;
    use Rain\Tpl;

    class Mailer {
        const USERNAME = "email@gmail.com";
        const PASSWORD = "<?senha?>";
        const NAMEFROM = "Hcode Store";
        private $mail;
        public function __construct($toAddress, $toName, $subject, $tplName, $data = array())
        {

            $config = array(
                "tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/email/",
                "cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
                "debug"         => false // set to false to improve the speed
               );
    
        Tpl::configure( $config );
        $tpl = new Tpl;

        foreach ($data as $key => $value) {
            $tpl->assign($key, $value);
        }
        $html = $tpl->draw($tplName, true);
        
        $this->mail = new \PHPMailer;
            //Tell PHPMailer to use SMTP
            $this->mail->isSMTP();
            $this->mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $this->mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $this->mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $this->mail->Host = 'smtp.gmail.com';
            //Set the SMTP port number - likely to be 25, 465 or 587
            $this->mail->Port = 587;
            $this->mail->SMTPSecure = 'tls';
            //Whether to use SMTP authentication
            $this->mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $this->mail->Username = Mailer::USERNAME;
            //Password to use for SMTP authentication
            $this->mail->Password = Mailer::PASSWORD;
            //Set who the message is to be sent from
            $this->mail->setFrom(Mailer::USERNAME, Mailer::NAMEFROM);
            //Set who the message is to be sent to
            $this->mail->addAddress($toAddress, $toName);
            //Set the subject line
            $this->mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $this->mail->msgHTML($html);
            //Replace the plain text body with one created manually
            $this->mail->AltBody = 'This is a plain-text message body';
    }

    public function send(){
        return $this->mail->send();
    }
}
?>