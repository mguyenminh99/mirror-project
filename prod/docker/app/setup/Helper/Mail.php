<?php

require_once 'Zend/Mail/Transport/Smtp.php';
require_once 'Zend/Mail.php';

class Mail {

    public function send($message){

        $currentTime = (new Datetime('now', new DateTimeZone('Asia/Tokyo')))->format('Y-m-d h:i:s');

        $mailSubject = "x-shopping-st ". getenv("HOST_NAME") ." init script failed";
        $mailFrom = 'system-notice@x-shopping-st.com';
        $mailTo   = 'dev-team@x-shopping-st.com';

        $config = [
            'auth'     => 'login',
            'username' => getenv('SEND_GRID_API_ACCOUNT'),
            'password' => getenv('SEND_GRID_API_KEY'),
            'port'     => 587,
            'ssl' => 'tls'
        ];

        try{

            $transport = new Zend_Mail_Transport_Smtp('smtp.sendgrid.net', $config);
            $mail = new Zend_Mail();
            $mail->setBodyText($currentTime . " " . $message);
            $mail->setFrom($mailFrom, 'System Notice');
            $mail->addTo($mailTo , 'Dev Team');
            $mail->setSubject($mailSubject);
            $mail->send($transport);

        }catch(\Exception $e){
            throw new Error('メール送信に失敗しました。');
        }
    }
}
