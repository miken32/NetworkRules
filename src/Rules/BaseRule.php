<?php

namespace Miken32\Validation\Network\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;
use Stringable;

abstract class BaseRule implements ValidatorAwareRule, ValidationRule
{
    protected Validator $validator;

    /** @var bool indicates whether we've been called as a result of Validator::extend() */
    protected bool $extended = false;

    /**
     * Handle validations by string e.g. 'netv4:182.44.1.0/24'
     *
     * @param string $attribute the field under validation
     * @param string $value the value to be validated
     * @param string[] $parameters any parameters passed to the rule
     * @param Validator $validator the validator instance
     * @return bool
     */
    public function extend(string $attribute, string $value, array $parameters, Validator $validator): bool
    {
        $this->extended = true;
        $this->setValidator($validator);
        $validator->setCustomMessages([$attribute => $this->message()]);
        $validator->addReplacer(
            substr(static::class, strrpos(static::class, '\\') + 1),
            static::class
        );

        return $this->doValidation($attribute, $value, ...$parameters);
    }

    /**
     * @param Validator $validator
     * @return static
     */
    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Handles validations by instance method, e.g. new Netv4('182.44.1.0/24')
     *
     * @param string $attribute the field under validation
     * @param mixed $value the value to be validated
     * @param Closure(string $message):(string|Stringable) $fail passed an error message on failure
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->doValidation($attribute, $value)) {
            $fail($this->message());
        }
    }

    /**
     * Custom replacer for failure messages; override per rule as needed; must be called
     * manually in the message() method when $extended == false
     *
     * @param string $message
     * @param string $attribute
     * @param string $rule
     * @param array<int, string|int> $parameters
     * @return string
     */
    public function replace(string $message, string $attribute, string $rule, array $parameters): string
    {
        return $message;
    }

    /**
     * Do the actual validation; used by both instance and extend methods
     *
     * @param string $attribute
     * @param string $value the value to be checked
     * @param string ...$parameters for string methods, the parameter array
     * @return bool
     */
    abstract public function doValidation(string $attribute, string $value, ...$parameters): bool;

    /**
     * Return the validation error message
     *
     * @return string
     */
    abstract public function message(): string;
}
