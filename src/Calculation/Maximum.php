<?php

namespace Undemanding\Difference\Calculation;

class Maximum
{
    /**
     * Maximum difference for all bitmap pixels.
     *
     * @param array $bitmap
     * @param int $width
     * @param int $height
     *
     * @return float
     */
    public function __invoke(array $bitmap, $width, $height)
    {
        $maximum = 0;

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($bitmap[$y][$x] > $maximum) {
                    $maximum = $bitmap[$y][$x];
                }
            }
        }

        return (float) $maximum;
    }
}
