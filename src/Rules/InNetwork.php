<?php

namespace Miken32\Validation\Network\Rules;

use Illuminate\Support\Arr;
use Miken32\Validation\Network\Util;

class InNetwork extends BaseRule
{
    /** @var array<string> */
    private array $networks;

    /**
     * @param string|array<string>|null $network
     */
    public function __construct(string|array|null $network = null)
    {
        $this->networks = Arr::wrap($network);
    }

    public function doValidation(string $attribute, string $value, ...$parameters): bool
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
        $message = __('The :attribute field must be an IP address within the :net network(s)');
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
        if (count($parameters) > 2) {
            array_walk($parameters, function(&$v, $k) use ($parameters) {
                if ($k < count($parameters) - 1) {
                    $v .= ',';
                }
                if ($k === count($parameters) - 2) {
                    $v .= ' or';
                }
            });
            $nets = implode(" ", $parameters);
        } elseif (count($parameters) === 2) {
            $nets = implode(" or ", $parameters);
        } else {
            $nets = "$parameters[0]";
        }

        return str_replace(':net', $nets, $message);
    }
}
