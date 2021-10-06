<?php

namespace Undemanding\Difference\Calculation;

class Percentage
{
    /**
     * Percentage of different pixels in the bitmap.
     *
     * @param array $bitmap
     * @param int $width
     * @param int $height
     *
     * @return float
     */
    public function __invoke(array $bitmap, $width, $height)
    {
        $total = 0;
        $different = 0;

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $total++;

                if ($bitmap[$y][$x] > 0) {
                    $different++;
                }
            }
        }

        return (float) (($different / $total) * 100);
    }
}
