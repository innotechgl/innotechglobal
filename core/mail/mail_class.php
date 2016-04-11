<?php

/**
 * @version        upr_mail.php 06.08.2010.
 * @package        Dajana's sCMS
 * @copyright           Copyright (C) 2010-2016 Dajana Nestorovic. All rights reserved.
 * @license       Open source, Dajana Nestorovic, All right reserved
 * @author       Dajana Nestorovic
 * @name        Mailovi
 *
 */
class mail
{

    public $from;
    public $from_mail;
    public $log_dir = '';

    public function __construct()
    {
        global $engine;
        # Set mail sender
        $this->from = $engine->settings->mail->fromName;
        $this->from_mail = $engine->settings->mail->fromMail;
    }

    /**
     * Slanje mail-a
     *
     * @param string $to
     * @param string $body
     * @param string $subject
     * @param string $fromaddress
     * @param string $fromname
     */
    public function send_mail($to, $body, $subject, $bcc = array())
    {
        global $engine;
        $body = strip_tags($body);
        $mime_boundary = md5(time());
        $eol = '\r\n';
        # Common Headers
        $headers = "From: " . $this->from . " <" . $this->from_mail . ">\r\n";
        $headers .= "Reply-To: " . $this->from_mail . " <" . $this->from_mail . ">\r\n";
        if (count($bcc) > 0) {
            $headers .= "Bcc: " . implode(";", $bcc) . "\r\n"; // these two to set reply address
        }
        $headers .= "Return-Path: " . $this->from_mail . "\r\n";    // these two to set reply address
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= "Message-ID: <" . time() . "-" . $this->from . ">" . "\r\n";
        $headers .= "X-Mailer: PHP v" . phpversion() . "\r\n";          // These two to help avoid spam-filters
        $headers .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-{$mime_boundary}\"\r\n";
        $body_dodaj = "--PHP-alt-{$mime_boundary}\r\n";
        $body_dodaj .= "Content-Type: plain/text; charset=\"utf-8\" \r\n";
        $body_dodaj .= "Content-Transfer-Encoding: 7bit" . "\r\n";
        $body_dodaj .= preg_replace(array("/<br \/>/", "/<br>/"), array("\r\n", "\r\n"), $body) . "\r\n\r\n";
        $body_dodaj .= "--PHP-alt-{$mime_boundary}\r\n";
        $body_dodaj .= "Content-Type: text/html; charset=\"utf-8\" \r\n";
        $body_dodaj .= "Content-Transfer-Encoding: 7bit" . "\r\n\r\n";
        $body_dodaj .= nl2br($body) . "\r\n\r\n";
        $body_dodaj .= "--PHP-alt-{$mime_boundary}--";
        /*
                echo "<h1>To:</h1>";
                echo $to."<br />";
                echo "<h1>Subject:</h1>";
                echo $subject."<br />";
                echo "<h1>Body:</h1>";
                echo $body_dodaj."<br />";
                echo "<h1>Headers:</h1>";
                echo $headers."<br />";
        */
        # send e-mail
        if (mail($to, $subject, $body_dodaj, $headers)) {
            return true;
        } else {
            return false;
        }
    }
}

?>