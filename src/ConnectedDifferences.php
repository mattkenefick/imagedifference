<?php

namespace Undemanding\Difference;

class ConnectedDifferences
{
    /**
     * @var array
     */
    private $bitmap;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var array
     */
    private $boundaries = [];

    /**
     * @param Difference $difference
     */
    public function __construct(Difference $difference)
    {
        $this->bitmap = $difference->getBitmap();
        $this->width = $difference->getWidth();
        $this->height = $difference->getHeight();

        $this->boundaries = $this->findBoundaries();
    }

    /**
     * Find separate boundaries.
     *
     * @return array
     */
    private function findBoundaries()
    {
        $pixels = [];

        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                if ($this->bitmap[$y][$x] > 0) {
                    $pixels["{$x}:{$y}"] = [
                        "group" => null,
                        "x" => $x,
                        "y" => $y,
                    ];
                }
            }
        }

        $group = 1;
        $boundaries = [];

        foreach ($pixels as $i => $pixel) {
            $adjacent = $this->adjacent($pixel);

            if (!$pixel["group"]) {
                foreach ($adjacent as $key) {
                    if (isset($pixels[$key]) and $pixels[$key]["group"]) {
                        $pixel["group"] = $pixels[$key]["group"];
                    }
                }
            }

            if (!$pixel["group"]) {
                $pixel["group"] = $group++;
            }

            foreach ($adjacent as $key) {
                if (isset($pixels[$key])) {
                    $pixels[$key]["group"] = $pixel["group"];
                }
            }
        }

        $groups = array_values(array_unique(array_map(function($pixel) {
            return $pixel["group"];
        }, $pixels)));

        foreach ($groups as $group) {
            $filtered = array_filter($pixels, function($pixel) use ($group) {
                return $pixel["group"] === $group;
            });

            $ax = $this->width;
            $bx = 0;
            $ay = $this->height;
            $by = 0;

            foreach ($filtered as $pixel) {
                $x = $pixel["x"];
                $y = $pixel["y"];

                if ($x > $bx) {
                    $bx = $x;
                }

                if ($x < $ax) {
                    $ax = $x;
                }

                if ($y > $by) {
                    $by = $y;
                }

                if ($y < $ay) {
                    $ay = $y;
                }
            }

            array_push($boundaries, [
                "top" => $ay,
                "right" => $bx,
                "bottom" => $by,
                "left" => $ax,
            ]);
        }

        return $boundaries;
    }

    /**
     * @return ConnectedDifferences
     */
    public function withJoinedBoundaries()
    {
        $keep = [];

        foreach ($this->boundaries as $boundary) {
            foreach ($keep as $i => $kept) {
                if ($this->intersect($boundary, $kept)) {
                    if ($boundary["top"] < $kept["top"]) {
                        $keep[$i]["top"] = $boundary["top"];
                    }

                    if ($boundary["right"] > $kept["right"]) {
                        $keep[$i]["right"] = $boundary["right"];
                    }

                    if ($boundary["bottom"] > $kept["bottom"]) {
                        $keep[$i]["bottom"] = $boundary["bottom"];
                    }

                    if ($boundary["left"] < $kept["left"]) {
                        $keep[$i]["left"] = $boundary["left"];
                    }

                    continue 2;
                }
            }

            array_push($keep, $boundary);
        }

        return $this->cloneWith("boundaries", $keep);
    }

    /**
     * @param string $property
     * @param mixed $value
     *
     * @return ConnectedDifferences
     */
    private function cloneWith($property, $value)
    {
        $clone = clone $this;
        $clone->$property = $value;

        return $clone;
    }

    /**
     * Labels for adjacent pixels.
     *
     * @param array $pixel
     *
     * @return array
     */
    private function adjacent($pixel)
    {
        $adjacent = [
            ($pixel["x"] - 1) . ":" . ($pixel["y"] - 1),
            ($pixel["x"] - 1) . ":" . $pixel["y"],
            ($pixel["x"] - 1) . ":" . ($pixel["y"] + 1),
            $pixel["x"] . ":" . ($pixel["y"] - 1),
            $pixel["x"] . ":" . ($pixel["y"] + 1),
            ($pixel["x"] + 1) . ":" . ($pixel["y"] - 1),
            ($pixel["x"] + 1) . ":" . $pixel["y"],
            ($pixel["x"] + 1) . ":" . ($pixel["y"] + 1),
        ];
        return $adjacent;
    }

    /**
     * Tell if two boundaries overlap.
     *
     * @param array $p
     * @param array $q
     *
     * @return bool
     */
    private function intersect(array $p, array $q)
    {
        return max($p["left"], $q["left"]) <= min($p["right"], $q["right"]) and max($p["top"], $q["top"]) <= min($p["bottom"], $q["bottom"]);
    }

    /**
     * @return array
     */
    public function boundaries()
    {
        return $this->boundaries;
    }
}
