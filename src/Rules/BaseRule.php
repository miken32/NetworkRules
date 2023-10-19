<?php

namespace Miken32\Validation\Network\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;
use Stringable;

if (!interface_exists(ValidationRule::class)) {
    // for Laravel 9 compatibility
    class_alias(Rule::class, ValidationRule::class);
}

abstract class BaseRule implements ValidatorAwareRule, ValidationRule
{
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

    /**
     * @param Validator $validator
     * @return static
     */
    public function setValidator($validator): static
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Run the validation method; called directly by instance method
     *
     * @param string $attribute the field under validation
     * @param mixed $value the value to be validated
     * @param Closure(string $message):string|Stringable $fail passed an error message on failure
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->doValidation($value)) {
            $fail($this->message());
        }
    }

    /**
     * Laravel 9 interface method
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return $this->doValidation($value);
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
