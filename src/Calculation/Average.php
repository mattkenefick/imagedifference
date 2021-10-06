<?php

namespace Undemanding\Difference\Calculation;

class Average
{
    /**
     * Average difference for all bitmap pixels.
     *
     * @param array $bitmap
     * @param int $width
     * @param int $height
     *
     * @return float
     */
    public function __invoke(array $bitmap, $width, $height)
    {
        $average = 0;

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $average += $bitmap[$y][$x];
            }
        }

        $average /= ($width * $height);

        return (float) $average;
    }
}
