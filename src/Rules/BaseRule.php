<?php

namespace Miken32\Validation\Network\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Translation\CreatesPotentiallyTranslatedStrings;
use Illuminate\Translation\PotentiallyTranslatedString;
use Illuminate\Validation\Validator;

abstract class BaseRule implements ValidatorAwareRule, ValidationRule
{
    use CreatesPotentiallyTranslatedStrings;
    protected Validator $validator;

    /** @var bool indicates whether we've been called as a result of Validator::extend() */
    protected bool $extended = false;

    /**
     * @param string $attribute the field under validation
     * @param string $value the value to be validated
     * @param array $parameters any parameters passed to the rule
     * @param Validator $validator the validator instance
     * @return bool
     */
    public function extend(string $attribute, string $value, array $parameters, Validator $validator): bool
    {
        $this->extended = true;
        $this->setValidator($validator);
        $validator->setCustomMessages([$attribute => $this->message()]);

        return $this->doValidation($value, ...$parameters);
    }

    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Run the validation method; called directly by instance method
     *
     * @param string $attribute the field under validation
     * @param mixed $value the value to be validated
     * @param Closure(string $message):PotentiallyTranslatedString $fail passed an error message on failure
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->doValidation($value)) {
            $fail($this->message());
        }
    }

    /**
     * Do the actual validation; used by both instance and extend methods
     *
     * @param string $value the value to be checked
     * @param mixed ...$parameters for string methods, the paramater array
     * @return bool
     */
    abstract public function doValidation(string $value, ...$parameters): bool;

    /**
     * Return the validation error message
     *
     * @return string
     */
    abstract public function message(): string;
}
