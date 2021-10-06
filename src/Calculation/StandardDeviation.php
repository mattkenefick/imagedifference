<?php

namespace Undemanding\Difference\Calculation;

class StandardDeviation
{
    /**
     * Standard deviation for all bitmap pixels.
     *
     * @param array $map
     * @param int $width
     * @param int $height
     * @param float $average
     *
     * @return float
     */
    public function __invoke(array $map, $width, $height, $average)
    {
        $standardDeviation = 0;

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $delta = $map[$y][$x] - $average;
                $standardDeviation += ($delta * $delta);
            }
        }

        $standardDeviation /= (($width * $height) - 1);
        $standardDeviation = sqrt($standardDeviation);

        return (float) $standardDeviation;
    }
}
