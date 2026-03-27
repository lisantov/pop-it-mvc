<?php

namespace Validators;

use Illuminate\Database\Capsule\Manager as Capsule;
use Src\Validator\AbstractValidator;

class UniqueValidator extends AbstractValidator
{

    protected string $message = 'Значение :field - уже занято';

    public function rule(): bool
    {
        return (bool)!Capsule::table($this->args[0])
            ->where($this->args[1], $this->value)->count();
    }
}