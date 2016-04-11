<?php

class modelSendMail{

    public function __construct()
    {


    }

    private function sendForm($fromName, $fromMail, $fromMessage){

        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'marko@innotechgl.com';
        $mail->Password = 'tuborg1981';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 465;

        $mail->setFrom('marko@innotechgl.com', 'Web forma');
        $mail->addAddress("'marko.sutija@outlook.com'", "'Marko Sutija'");

        // Optional name
        $mail->isHTML(true);

        $mail->Subject = "Forma sa sajta";
        $mail->Body    = '<p>From: '.$fromName.'</p>';

        $mail->send();
    }

}