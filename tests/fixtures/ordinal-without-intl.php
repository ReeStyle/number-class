<?php

namespace ReeStyleIt\NumberClass\Conversion {
    function class_exists(string $class, bool $autoload = true): bool
    {
        return $class === \NumberFormatter::class ? false : \class_exists($class, $autoload);
    }
}

namespace {
    require __DIR__ . '/../../vendor/autoload.php';

    try {
        $method = $argv[1] ?? 'format';
        echo new \ReeStyleIt\NumberClass\Conversion\Ordinal(1)->$method();
        exit(1);
    } catch (\ReeStyleIt\NumberClass\Exceptions\OrdinalFormattingException $exception) {
        echo $exception->getMessage();
    }
}
