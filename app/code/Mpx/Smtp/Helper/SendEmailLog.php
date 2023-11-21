<?php

namespace Mpx\Smtp\Helper;

use \Zend_Mail_Transport_Smtp;
use \Zend_Mail;

class SendEmailLog
{

    /**
     * send email log for install script or log exception
     *
     * @param $message
     * @param null $subject
     */
    public function sendEmail($message, $subject)
    {
        $mailFrom = 'system-notice@x-shopping-st.com';
        $mailTo   = 'dev-team@x-shopping-st.com';

        $config = [
            'auth'     => 'login',
            'username' => getenv('SEND_GRID_API_ACCOUNT'),
            'password' => getenv('SEND_GRID_API_KEY'),
            'port'     => 587,
            'ssl' => 'tls'
        ];

        try {
            $transport = new Zend_Mail_Transport_Smtp('smtp.sendgrid.net', $config);
            $mail = new Zend_Mail();
            $mail->setBodyText($message);
            $mail->setFrom($mailFrom, 'System Notice');
            $mail->addTo($mailTo , 'Dev Team');
            $mail->setSubject($subject);
            $mail->send($transport);
        }catch (\Exception $e){
            echo 'Error sending email: ' . $e->getMessage();
        }
    }
}
