<?php

namespace Undemanding\Difference\Transformation;

class Difference
{
    /**
     * Difference between all pixels of two images.
     *
     * @param array $bitmap1
     * @param array $bitmap2
     * @param int $width
     * @param int $height
     * @param callable $method
     *
     * @return array
     */
    public function __invoke(array $bitmap1, array $bitmap2, $width, $height, callable $method)
    {
        $new = [];

        for ($y = 0; $y < $height; $y++) {
            $new[$y] = [];

            for ($x = 0; $x < $width; $x++) {
                $new[$y][$x] = $method(
                    $bitmap1[$y][$x],
                    $bitmap2[$y][$x]
                );
            }
        }

        return $new;
    }
}
