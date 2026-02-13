<?php

namespace Miken32\Validation\Network\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;

class ArrProvider extends ServiceProvider
{
    public function boot(): void
    {
        Arr::macro(
            'oxfordplode',
            /**
             * Implode with an Oxford comma
             *
             * @param scalar[] $array
             * @param string $conjunction
             * @return string
             */
            function(array $array, string $conjunction = 'and'): string
            {
                $array = array_filter($array, fn ($v) => is_scalar($v));
                if (count($array) === 1) {
                    return strval(array_shift($array));
                }
                $conjunction = ' ' . trim($conjunction) . ' ';
                if (count($array) === 2) {
                    return implode($conjunction, $array);
                }
                $t = array_pop($array);

                return implode(', ', $array) . ',' . $conjunction . $t;
            }
        );
    }
}
