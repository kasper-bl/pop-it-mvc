<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class FileValidator extends AbstractValidator
{
    protected string $message = 'Поле :field должно быть изображением (JPG, PNG, GIF, WEBP)';

    public function rule(): bool
    {
        if (empty($this->value['tmp_name'])) {
            return true; // файл не загружен — не проверяем
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $this->value['tmp_name']);
        finfo_close($finfo);
        
        return in_array($mimeType, $allowedTypes);
    }
}