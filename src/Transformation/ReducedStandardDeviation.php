<?php

namespace Undemanding\Difference\Transformation;

class ReducedStandardDeviation
{
    /**
     * New map of pixels with those in the standard deviation removed.
     *
     * @param array $bitmap
     * @param int $width
     * @param int $height
     * @param float $deviation
     *
     * @return array
     */
    public function __invoke(array $bitmap, $width, $height, $deviation)
    {
        $new = array_slice($bitmap, 0);

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if (abs($new[$y][$x]) < $deviation) {
                    $new[$y][$x] = 0;
                }
            }
        }

        return $new;
    }
}
