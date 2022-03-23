<?php

namespace Api\Infra;

use Api\Helper\ResponseError;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use stdClass;

class EmailForClient
{
    use ResponseError;

    private PHPMAILER $mail;
    private stdClass $data;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->data = new \stdClass();
        $this->mail->isSMTP();
        $this->mail->isHTML(true);
        $this->mail->setLanguage('br');
//        $this->mail->SMTPDebug = 4;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->CharSet = PHPMailer::CHARSET_UTF8;
        $this->mail->SMTPAuth = true;
        $this->mail->Host = HOST_MAIL;
        $this->mail->Port = PORT_MAIL;
        $this->mail->Username = USER_MAIL;
        $this->mail->Password = PASS_MAIL;
    }

    public function add(string $subject, string $body, string $recipient_email, string $recipient_name): EmailForClient
    {
        $this->data->subject = $subject;
        $this->data->body = $body;
        $this->data->recipient_email = $recipient_email;
        $this->data->recipient_name = $recipient_name;
        return $this;
    }

    public function attach(string $filetPath, string $fileName): EmailForClient
    {
        $this->data->attach[$filetPath] = $fileName;
        return $this;
    }

    public function send($fromEmail = FROM_EMAIL_MAIL, $fromName = FROM_NAME_MAIL): bool
    {
        try {
            $this->mail->Subject = $this->data->subject;
            $this->mail->msgHTML($this->data->body);
            $this->mail->addAddress(
                $this->data->recipient_email,
                $this->data->recipient_name
            );
            $this->mail->setFrom($fromEmail, $fromName);

            if (!empty($this->data->attach)) {
                foreach ($this->data->attach as $path => $name) {
                    $this->mail->addAttachment($path, $name);
                }
            }
            $this->mail->send();
            return true;
        } catch (Exception) {
            $this->responseCatchError('Email não enviado, por favor verifique o endereço de email');
        }
    }
}
