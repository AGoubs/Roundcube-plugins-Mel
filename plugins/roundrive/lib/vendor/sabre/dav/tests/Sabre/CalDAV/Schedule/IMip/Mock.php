<?php

namespace Sabre\CalDAV\Schedule\IMip;

/**
 * iMIP handler.
 *
 * This class is responsible for sending out iMIP messages. iMIP is the
 * email-based transport for iTIP. iTIP deals with scheduling operations for
 * iCalendar objects.
 *
 * If you want to customize the email that gets sent out, you can do so by
 * extending this class and overriding the sendMessage method.
 * 
 * @copyright Copyright (C) 2007-2015 fruux GmbH (https://fruux.com/).
 * @author Evert Pot (http://evertpot.com/)
 * @license http://sabre.io/license/ Modified BSD License
 */
class Mock extends \Sabre\CalDAV\Schedule\IMip {

    protected $emails = array();

    /**
     * This function is reponsible for sending the actual email.
     *
     * @param string $to Recipient email address
     * @param string $subject Subject of the email
     * @param string $body iCalendar body
     * @param array $headers List of headers
     * @return void
     */
    protected function mail($to, $subject, $body, array $headers) {

        $this->emails[] = array(
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
            'headers' => $headers,
        );

    }

    public function getSentEmails() {

        return $this->emails;

    }


}
