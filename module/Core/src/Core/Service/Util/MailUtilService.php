<?php

namespace Core\Service\Util;

use Core\Service\Service;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;

/**
 * Implements basic functions to send e-mail with ZF2
 *
 * @author Lucas dos Santos Abreu <lucas.s.abreu@gmail.com>
 */
class MailUtilService extends Service {

    /**
     * Send a e-mail by the params.
     * 
     * @param string $body Content will be send
     * @param string $mimeType Mime-Type of <code>$body</code>
     * @param array $to Array with format email => names
     * @param type $cc (optional) Array with format email => names
     * @param type $cco (optional) Array with format email => names
     */
    public function sendEmail($body, $mimeType, $to, $cc = array(), $cco = array()) {

        $config = $this->getService('Config');
        /* @var $config array */

        if (!isset($config['email_sending']) || !isset($config['email_sending']['from'])) {
            throw new \InvalidArgumentException("Email sending params not exists in config.");
        }

        $mail = new Message();
        $mail->setBody($body);
        $mail->setFrom('Freeaqingme@example.org', 'Dolf');
        $mail->addTo('matthew@example.com', 'Matthew');
        $mail->setSubject('TestSubject');

        $transport = new Sendmail('-freturn_to_me@example.com');
        $transport->send($mail);
    }

}

?>
