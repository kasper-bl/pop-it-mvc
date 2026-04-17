<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class DateRangeValidator extends AbstractValidator
{
    protected string $message = 'Дата "до" не может быть раньше даты "от"';

    public function rule(): bool
    {
        $dateFrom = $this->args[0] ?? null;
        $dateTo = $this->value;
        
        if (empty($dateFrom) || empty($dateTo)) {
            return true;
        }
        
        return strtotime($dateTo) >= strtotime($dateFrom);
    }
}