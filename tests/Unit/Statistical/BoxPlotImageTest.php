<?php

namespace Tests\Unit\Statistical;

use GdImage;
use ReeStyleIt\NumberClass\Exceptions\StatisticalException;
use Symfony\Component\Process\Process;

use function ReeStyleIt\NumberClass\boxPlot;

it('renders independent image objects without changing statistical results', function () {
    $plot = boxPlot([1, 2, 3, 4, 5, 6, 7]);
    $summary = $plot->get();
    $image = $plot->image();

    expect($image)->toBeInstanceOf(GdImage::class)
        ->and(imagesx($image))->toBe(800)
        ->and(imagesy($image))->toBe(300)
        ->and($plot->image())->not->toBe($image)
        ->and($plot->get())->toBe($summary)
        ->and(imagecolorat($image, 400, 160))->toBe(0xdc2626)
        ->and(imagecolorat($image, 200, 160))->toBe(0xdbeafe);
})->skip(! extension_loaded('gd'), 'GD is optional.');

it('renders actual outlier marks beyond the whiskers', function () {
    $image = boxPlot([-100, 1, 2, 3, 4, 5, 6, 7, 100])->image();

    expect(imagecolorat($image, 68, 160))->toBe(0x7e22ce)
        ->and(imagecolorat($image, 732, 160))->toBe(0x7e22ce);
})->skip(! extension_loaded('gd'), 'GD is optional.');

it('supports PNG encoding and custom image dimensions', function () {
    $image = boxPlot([1, 2, 3])->image(width: 640, height: 240);
    ob_start();

    try {
        $encoded = imagepng($image);
        $png = ob_get_contents();
    } finally {
        ob_end_clean();
    }

    $size = getimagesizefromstring($png);

    expect($encoded)->toBeTrue()
        ->and(substr($png, 0, 8))->toBe("\x89PNG\r\n\x1a\n")
        ->and([$size[0], $size[1], $size[2]])->toBe([640, 240, IMAGETYPE_PNG]);
})->skip(! extension_loaded('gd') || ! function_exists('imagepng'), 'PNG rendering requires GD with PNG support.');

it('renders constant, singleton, negative, and extreme numeric ranges', function (array $sample) {
    $image = boxPlot($sample)->image(320, 180);

    expect(imagesx($image))->toBe(320)
        ->and(imagesy($image))->toBe(180);
})->with([
    [[0]], [[7, 7, 7]], [[-10, -8, -5, -2]], [[1e-250, 2e-250, 3e-250]],
    [[-PHP_FLOAT_MAX, 0, 0, 0, 0, 0, 0, 0, 0, 0, PHP_FLOAT_MAX]],
])->skip(! extension_loaded('gd'), 'GD is optional.');

it('validates dimensions before allocating the image', function (int $width, int $height) {
    expect(fn () => boxPlot([1, 2, 3])->image($width, $height))->toThrow(StatisticalException::class, 'image dimensions');
})->with([[0, 300], [319, 300], [800, 179], [4097, 300], [800, 4097]]);

it('keeps calculations available when GD rendering is unavailable', function () {
    $process = new Process([
        PHP_BINARY, '-n', '-d', 'disable_functions=imagecreatetruecolor',
        dirname(__DIR__, 2) . '/fixtures/box-plot-without-gd.php',
    ]);
    $process->mustRun();

    expect($process->getOutput())->toBe("Box plot calculations work without GD; image rendering reports the missing extension.\n");
});
