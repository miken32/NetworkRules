<?php

namespace Miken32\Validation\Network\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class ArrProvider extends ServiceProvider
{
    public function boot(): void
    {
        Arr::macro(
            'oxfordplode',
            /**
             * Implode with an Oxford comma
             *
             * @param string[]|int[]|float[]|bool[] $array
             * @param string $conjunction
             * @return string
             */
            function(array $array, string $conjunction = 'and'): string
            {
                if (array_filter($array, fn ($v) => !is_scalar($v))) {
                    throw new InvalidArgumentException();
                }
                if (count($array) === 1) {
                    return strval(array_first($array));
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
