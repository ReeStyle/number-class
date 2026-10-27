<?php

namespace ReeStyleIt\NumberClass\Statistical\BoxPlot;

use GdImage;
use ReeStyleIt\NumberClass\Exceptions\BoxPlotImageException;
use ReeStyleIt\NumberClass\Exceptions\StatisticalException;

readonly class Image
{
    public function __construct(private Result $result) {}

    public function render(int $width = 800, int $height = 300): GdImage
    {
        if ($width < 320 || $height < 180 || $width > 4096 || $height > 4096) {
            throw new StatisticalException('Box plot image dimensions must be between 320 x 180 and 4096 x 4096 pixels.');
        }

        if (! extension_loaded('gd') || ! function_exists('imagecreatetruecolor')) {
            throw new BoxPlotImageException();
        }

        $image = imagecreatetruecolor($width, $height);

        if ($image === false) {
            throw new BoxPlotImageException('Unable to allocate a box plot image.');
        }

        $white = imagecolorallocate($image, 255, 255, 255);
        $ink = imagecolorallocate($image, 30, 41, 59);
        $grid = imagecolorallocate($image, 226, 232, 240);
        $blue = imagecolorallocate($image, 59, 130, 246);
        $fill = imagecolorallocate($image, 219, 234, 254);
        $red = imagecolorallocate($image, 220, 38, 38);
        $purple = imagecolorallocate($image, 126, 34, 206);
        imagefill($image, 0, 0, $white);
        imagestring($image, 5, 24, 14, 'Box plot', $ink);

        imagefilledrectangle($image, 24, 43, 36, 53, $fill);
        imagerectangle($image, 24, 43, 36, 53, $blue);
        imagestring($image, 2, 44, 42, 'Q1-Q3', $ink);
        imageline($image, 116, 42, 116, 54, $red);
        imagestring($image, 2, 124, 42, 'Median', $ink);
        imageellipse($image, 208, 48, 7, 7, $purple);
        imagestring($image, 2, 220, 42, 'Outlier', $ink);

        $left = 64;
        $right = $width - 64;
        $top = 70;
        $axis = $height - 50;
        $center = (int) round(($top + $axis) / 2);
        $boxHalfHeight = min(24, intdiv($axis - $top, 4));
        $span = $this->result->max - $this->result->min;
        $constant = $span == 0;
        $intervals = min(5, max(2, intdiv($right - $left, 110)));

        for ($tick = 0; $tick <= ($constant ? 0 : $intervals); $tick++) {
            $ratio = $constant ? 0.5 : $tick / $intervals;
            $x = (int) round($left + (($right - $left) * $ratio));
            // A weighted sum remains finite even when max - min overflows.
            $value = $constant ? $this->result->min
                : (((1 - $ratio) * $this->result->min) + ($ratio * $this->result->max));
            $label = sprintf('%.5g', $value);
            imageline($image, $x, $top, $x, $axis, $grid);
            imageline($image, $x, $axis, $x, $axis + 5, $ink);
            $textX = (int) round($x - ((strlen($label) * imagefontwidth(2)) / 2));
            imagestring($image, 2, $textX, $axis + 10, $label, $ink);
        }

        imageline($image, $left, $axis, $right, $axis, $ink);
        $lower = $this->position($this->result->lowerWhisker, $left, $right);
        $upper = $this->position($this->result->upperWhisker, $left, $right);
        $q1 = $this->position($this->result->q1, $left, $right);
        $q3 = $this->position($this->result->q3, $left, $right);
        $median = $this->position($this->result->median, $left, $right);

        imagesetthickness($image, 2);
        imageline($image, $lower, $center, $upper, $center, $ink);
        imageline($image, $lower, $center - 12, $lower, $center + 12, $ink);
        imageline($image, $upper, $center - 12, $upper, $center + 12, $ink);
        imagefilledrectangle($image, $q1, $center - $boxHalfHeight, $q3, $center + $boxHalfHeight, $fill);
        imagerectangle($image, $q1, $center - $boxHalfHeight, $q3, $center + $boxHalfHeight, $blue);
        imageline($image, $median, $center - $boxHalfHeight, $median, $center + $boxHalfHeight, $red);

        imagesetthickness($image, 1);

        foreach ($this->result->outliers as $outlier) {
            imageellipse($image, $this->position($outlier, $left, $right), $center, 8, 8, $purple);
        }

        return $image;
    }

    private function position(int|float $value, int $left, int $right): int
    {
        $min = $this->result->min;
        $max = $this->result->max;
        $span = $max - $min;

        if ($span == 0) {
            return (int) round(($left + $right) / 2);
        }

        if (is_finite($span)) {
            $ratio = ($value - $min) / $span;
        } else {
            $scale = max(abs($min), abs($max));
            $ratio = (($value / $scale) - ($min / $scale)) / (($max / $scale) - ($min / $scale));
        }

        return (int) round($left + (($right - $left) * max(0.0, min(1.0, $ratio))));
    }
}
