<?php

namespace Miken32\Validation\Network\Rules;

use Illuminate\Support\Arr;
use Miken32\Validation\Network\Util;

class OutsideNetwork extends BaseRule
{
    /** @var array<int, string> */
    private array $networks;

    /**
     * @param string|array<int, string>|null $network
     */
    public function __construct(string|array|null $network = null)
    {
        $this->networks = is_array($network) ? $network : ($network ? [$network] : []);
    }

    public function doValidation(string $attribute, string $value, ...$parameters): bool
    {
        if ($this->extended) {
            $this->networks = array_values($parameters);
        }

        foreach ($this->networks as $network) {
            $result = Util::addressWithinNetwork($value, $network);
            if ($result === true) {
                return false;
            }
        }

        return true;
    }

    public function message(): string
    {
        $message = __('The :attribute field must be an IP address outside the :net network(s)');
        if (!$this->extended) {
            $message = $this->replace($message, "", "", $this->networks);
        }

        return $message;
    }

    /**
     * Generate a nice list when multiple networks are supplied
     */
    public function replace(string $message, string $attribute, string $rule, array $parameters): string
    {
        $nets = Arr::oxfordplode($parameters);

        return str_replace(':net', $nets, $message);
    }
}
