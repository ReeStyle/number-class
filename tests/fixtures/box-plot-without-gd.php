<?php

use ReeStyleIt\NumberClass\Exceptions\BoxPlotImageException;

use function ReeStyleIt\NumberClass\boxPlot;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

if (extension_loaded('gd') && function_exists('imagecreatetruecolor')) {
    throw new RuntimeException('This check requires GD rendering to be unavailable.');
}

$plot = boxPlot([1, 2, 3, 4, 5]);

if ($plot->get()->median !== 3) {
    throw new RuntimeException('Box plot calculations must work without GD.');
}

try {
    $plot->image();
} catch (BoxPlotImageException $exception) {
    if (! str_contains($exception->getMessage(), 'GD extension')) {
        throw new RuntimeException('Expected a clear optional extension error.');
    }

    echo "Box plot calculations work without GD; image rendering reports the missing extension.\n";
    exit(0);
}

throw new RuntimeException('Rendering without GD must throw BoxPlotImageException.');
