<?php

namespace Undemanding\Difference\Method;

class EuclideanDistance
{
    /**
     * RGB color distance for the same pixel in two images.
     *
     * @link https://en.wikipedia.org/wiki/Euclidean_distance
     *
     * @param array $p
     * @param array $q
     *
     * @return float
     */
    public function __invoke(array $p, array $q)
    {
        $r = $p["r"] - $q["r"];
        $r *= $r;

        $g = $p["g"] - $q["g"];
        $g *= $g;

        $b = $p["b"] - $q["b"];
        $b *= $b;

        return (float) sqrt($r + $g + $b);
    }
}
