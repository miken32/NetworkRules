<?php

namespace Miken32\Validation\Network\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory as ValidationFactory;
use Miken32\Validation\Network\Rules;

class ValidationProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->callAfterResolving('validator', function (ValidationFactory $validator) {
            // by default the class' validate() method is called but we specify extend()
            // here so that the validate() method can be used in FormRequest validation
            $validator->extend('private_ip', Rules\IpPrivateAddress::class . '@extend');
            $validator->extend('ip_or_net', Rules\IpAddressOrSubnet::class . '@extend');
            $validator->extend('private_ipv4', Rules\Ipv4PrivateAddress::class . '@extend');
            $validator->extend('netv4', Rules\Ipv4Network::class . '@extend');
            $validator->extend('private_ipv6', Rules\Ipv6PrivateAddress::class . '@extend');
            $validator->extend('netv6', Rules\Ipv6Network::class . '@extend');
            $validator->extend('in_network', Rules\AddressInSubnet::class . '@extend');
        });
    }
}
