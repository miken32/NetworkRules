<?php

namespace Miken32\Validation\Network\Rules;

use Illuminate\Support\Arr;
use Miken32\Validation\Network\Util;

class InNetwork extends BaseRule
{
    private array $networks;

    public function __construct(string|array|null $network = null)
    {
        $this->networks = Arr::wrap($network);
    }

    public function doValidation(string $value, ...$parameters): bool
    {
        if ($this->extended) {
            $this->networks = $parameters;
        }

        foreach ($this->networks as $network) {
            $result = Util::addressWithinNetwork($value, $network);
            if ($result === true) {
                return true;
            }
        }

        return false;
    }

    public function message(): string
    {
        if (isset($this->networks) && count($this->networks) > 1) {
            return __('The :attribute field must be a valid IP address within the allowed subnets');
        }

        return sprintf(
            __('The :attribute field must be a valid IP address within the %s subnet'),
            $this->networks[0] ?? __('specified')
        );
    }
}
