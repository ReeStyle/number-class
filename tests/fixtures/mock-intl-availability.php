<?php

namespace ReeStyleIt\NumberClass\Enums {
    function class_exists(string $class, bool $autoload = true): bool
    {
        return $class === \NumberFormatter::class ? false : \class_exists($class, $autoload);
    }
}

namespace ReeStyleIt\NumberClass\Format {
    function class_exists(string $class, bool $autoload = true): bool
    {
        return $class === \NumberFormatter::class ? false : \class_exists($class, $autoload);
    }
}
