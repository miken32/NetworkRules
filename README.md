<p align="center">
<big>A Collection of Rules<br/>
for the <i>Reliable Validation</i></big><br/>
of<br/>
Internet Protocol Addreſſes,<br/>
Networks, and Subnets<br/>
<i>which the author hopes will be found uſeful to</i><br/>
<i><b>Laravel Programmers</b> &c.</i>
</p>

## Installation
```sh
composer require miken32/network-rules
```

## Available Validation Rules
Here is a list of the available rules and their usage.

[In Network](#in_networkcidr)<br/>
[IP Or Net](#ip_or_net)<br/>
[Netv4](#netv4lowhigh)<br/>
[Netv6](#netv6lowhigh)<br/>
[Private IP](#private_ip)<br/>
[Private IPv4](#private_ipv4)<br/>
[Private IPv6](#private_ipv6)<br/>
[Routable IP](#routable_ip)<br/>
[Routable IPv4](#routable_ipv4)<br/>
[Routable IPv6](#routable_ipv6)

### in_network:cidr,...
The field under validation must be an IP address within one of the given networks. The networks must be given in CIDR notation, and may be either IPv4 or IPv6 networks.
```none
'ip4_address' => 'in_network:192.168.0.1/24',
'some_address' => 'in_network:192.168.0.0/24,192.168.1.0/24,192.168.2.0/24',
'ip6_address' => 'in_network:fd03:224f:a5c3:99ae::0/64'
```

### ip_or_net
The field under validation must be an IP address or network in CIDR notation. The address or network may be either IPv4 or IPv6.

### netv4:_low_,_high_
The field under validation must be an IPv4 network in CIDR notation. If provided, the number of bits in the mask must be between `low` and `high`; otherwise, default values of 0 and 32 are used.
```none
'bounded_network'   => 'netv4:20,24',
'unbounded_network' => 'netv4'
```

### netv6:_low_,_high_
The field under validation must be an IPv6 network in CIDR notation. If provided, the number of bits in the mask must be between `low` and `high`; otherwise, default values of 0 and 128 are used.
```none
'bounded_network'   => 'netv6:56,64'
'unbounded_network' => 'netv6'
```

### private_ip
The field under validation must be a private IPv4 or IPv6 address. "Private" address spaces are defined as follows:
* 10.0.0.0/8
* 172.16.0.0/12
* 192.168.0.0/16
* fc00::/7

The following networks are considered _reserved_, not _private_. They are **not** considered valid by this rule:
* 0.0.0.0/8
* 127.0.0.0/8
* 169.254.0.0/16
* 240.0.0.0/4
* ::/128
* ::1/128
* 2001:10::/28
* 2001:db8::/32
* 3ffe::/16
* 5f00::/8
* fe80::/10

### private_ipv4
The field under validation must be a private IPv4 addresses. The networks considered private are described in the `private_ip` rule.

### private_ipv6
The field under validation must be a private IPv6 addresses. The networks considered private are described in the `private_ip` rule.

### routable_ip
The field under validation must be a globally routable IPv4 or IPv6 address. This excludes all private and reserved ranges, as detailed the the `private_ip` rule.

### routable_ipv4
The field under validation must be a globally routable IPv4 address. This excludes all private and reserved ranges, as detailed the the `private_ip` rule.

### routable_ipv6
The field under validation must be a globally routable IPv6 address. This excludes all private and reserved ranges, as detailed the the `private_ip` rule.

## Usage
The included validation rules can be used either as traditional string-based validation rules or as instantiated classes. The following code blocks perform identical validations.
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MyFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [

          'address'      => ['in_network:192.168.10.0/24'], // must be an IPv4 address in the specified network
          'subnet'       => ['netv4'], // must be an IPv4 CIDR network
          'ipv6_subnet'  => ['netv6:48,56'], // must be an IPv6 CIDR network between 48 and 56 bits
        ];
    }
}
```

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Miken32\Validation\Network\Rules;

class AnotherFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
          'address'      => [new Rules\InNetwork('192.168.10.0/24')], // must be an IPv4 address in the specified network
          'subnet'       => [new Rules\Netv4()], // must be an IPv4 CIDR network
          'ipv6_subnet'  => [new Rules\Netv6(48, 56)], // must be an IPv6 CIDR network between 48 and 56 bits
        ];
    }
}
```
