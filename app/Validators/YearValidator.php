<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class YearValidator extends AbstractValidator
{
    protected string $message = 'Год публикации не может быть в будущем';

    public function rule(): bool
    {
        if (empty($this->value)) {
            return true;
        }
        
        $year = (int)date('Y', strtotime($this->value));
        $currentYear = (int)date('Y');
        
        return $year <= $currentYear;
    }
}