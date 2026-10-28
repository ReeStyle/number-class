<?php

namespace ReeStyleIt\NumberClass\Number {
    function class_exists(string $class, bool $autoload = true): bool
    {
        return $class === \NumberFormatter::class ? false : \class_exists($class, $autoload);
    }
}

namespace {
    require __DIR__ . '/../../vendor/autoload.php';

    try {
        echo new \ReeStyleIt\NumberClass\Number(21)->wordify;
        exit(1);
    } catch (\ReeStyleIt\NumberClass\Exceptions\NumberFormattingException $exception) {
        echo $exception->getMessage();
    }
}
