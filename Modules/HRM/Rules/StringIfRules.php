<?php

use Illuminate\Contracts\Validation\Rule;

class StringIfRule implements Rule
{
    protected $otherField;

    protected $value;

    public function __construct($otherField, $value)
    {
        $this->otherField = $otherField;
        $this->value = $value;
    }

    public function passes($attribute, $value)
    {
        $otherValue = request()->input($this->otherField);
        if ($otherValue == $this->value) {
            return is_string($value);
        }

        return true;
    }

    public function message()
    {
        return 'The :attribute must be a string if :other is :value.';
    }
}
