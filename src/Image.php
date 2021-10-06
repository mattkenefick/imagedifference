<?php

namespace Undemanding\Difference;

use InvalidArgumentException;
use Undemanding\Difference\Transformation;

class Image
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
     * @param string $path
     */
    public function __construct($path)
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException("image not found");
        }

        $image = $this->createImage($path);

        $this->bitmap = $this->createBitmap(
            $image,
            $this->width = imagesx($image),
            $this->height = imagesy($image)
        );
    }

    /**
     * Create new image resource from image path.
     *
     * @param string $path
     *
     * @return resource
     *
     * @throws InvalidArgumentException
     */
    private function createImage($path)
    {
        $info = getimagesize($path);
        $type = $info[2];

        $image = null;

        if ($type == IMAGETYPE_JPEG) {
            $image = imagecreatefromjpeg($path);
        }
        if ($type == IMAGETYPE_GIF) {
            $image = imagecreatefromgif($path);
        }
        if ($type == IMAGETYPE_PNG) {
            $image = imagecreatefrompng($path);
        }

        if (!$image) {
            throw new InvalidArgumentException("image invalid");
        }

        return $image;
    }

    /**
     * Creates new bitmap from image resource.
     *
     * @param resource $image
     * @param int $width
     * @param int $height
     *
     * @return array
     */
    private function createBitmap($image, $width, $height)
    {
        $bitmap = [];

        for ($y = 0; $y < $height; $y++) {
            $bitmap[$y] = [];

            for ($x = 0; $x < $width; $x++) {
                $color = imagecolorat($image, $x, $y);

                $bitmap[$y][$x] = [
                    "r" => ($color >> 16) & 0xFF,
                    "g" => ($color >> 8) & 0xFF,
                    "b" => $color & 0xFF
                ];
            }
        }

        return $bitmap;
    }

    /**
     * Difference between two bitmap states.
     *
     * @param Image $image
     * @param callable $method
     *
     * @return Difference
     */
    public function difference(Image $image, callable $method)
    {
        $transformation = new Transformation\Difference();

        $bitmap = $transformation(
            $this->bitmap, $image->bitmap, $this->width, $this->height, $method
        );

        return new Difference(
            $this->cloneWith("bitmap", $bitmap)
        );
    }

    /**
     * @param string $property
     * @param mixed $value
     *
     * @return Image
     */
    private function cloneWith($property, $value)
    {
        $clone = clone $this;
        $clone->$property = $value;

        return $clone;
    }

    /**
     * @return array
     */
    public function getBitmap()
    {
        return $this->bitmap;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }
}
