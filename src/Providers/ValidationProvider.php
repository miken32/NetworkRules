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
            $validator->extend('private_ip', Rules\PrivateIp::class . '@extend');
            $validator->extend('private_net', Rules\PrivateNet::class . '@extend');
            $validator->extend('routable_ip', Rules\RoutableIp::class . '@extend');
            $validator->extend('routable_net', Rules\RoutableNet::class . '@extend');
            $validator->extend('ip_or_net', Rules\IpOrNet::class . '@extend');
            $validator->extend('routable_ip_or_net', Rules\RoutableIpOrNet::class . '@extend');
            $validator->extend('private_ipv4', Rules\PrivateIpv4::class . '@extend');
            $validator->extend('routable_ipv4', Rules\RoutableIpv4::class . '@extend');
            $validator->extend('netv4', Rules\Netv4::class . '@extend');
            $validator->extend('routable_netv4', Rules\RoutableNetv4::class . '@extend');
            $validator->extend('private_ipv6', Rules\PrivateIpv6::class . '@extend');
            $validator->extend('routable_ipv6', Rules\RoutableIpv6::class . '@extend');
            $validator->extend('netv6', Rules\Netv6::class . '@extend');
            $validator->extend('routable_netv6', Rules\RoutableNetv6::class . '@extend');
            $validator->extend('in_network', Rules\InNetwork::class . '@extend');
        });
    }
}
