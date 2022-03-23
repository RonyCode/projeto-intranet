<?php

namespace Api\Helper;

trait TemplateEmail
{
    public function bodyEmail(array $data): string
    {
        extract($data);
        ob_start();
        require __DIR__ . '/../../src/Templates/BodyMail.php';
        return ob_get_clean();
    }
}
