<?php

namespace Undemanding\Difference\Transformation;

class Scale
{
    /**
     * New map of pixels scaled by a constant factor.
     *
     * @param array $bitmap
     * @param int $width
     * @param int $height
     * @param float $maximum
     * @param float $factor
     *
     * @return array
     */
    public function __invoke(array $bitmap, $width, $height, $maximum, $factor)
    {
        $new = [];

        for ($y = 0; $y < $height; $y++) {
            $new[$y] = [];

            for ($x = 0; $x < $width; $x++) {
                $new[$y][$x] = ($bitmap[$y][$x] / $maximum) * $factor;
            }
        }

        return $new;
    }
}
