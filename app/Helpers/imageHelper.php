<?php namespace App\Helpers;

/**
* @author FreebieVectors.com
*
* General image utilities
*/
class ImageHelper
{

    /**
    * Full path to the image file
    *
    * @var String
    */
    public $file;

    /**
    * Image extension
    *
    * @var String
    */
    public $extension;

    /**
    * Image information
    *
    * @var mixed
    */
    public $info;

    /**
    * Image GD PHP resource
    *
    * @var resource
    */
    public $resource;

    /**
    * Threshold of flesh color in image to consider in pornographic,
    * see page 302.
    *
    * @var float
    */
    public $threshold = .5;

    /**
    * Pixel count to iterate over. Too increase speed, set it higher and it will
    * skip some pixels.
    *
    * @var int
    */
    public $iteratorIncrement = 1;

    /**
    * Cb and Cr value bounds. See page 300
    *
    * @var array
    */
    public $boundsCbCr = array(80, 120, 133, 173);

    /**
    * Exclude white colors above this RGB color intensity
    *
    * @var int
    */
    public $excludeWhite = 250;

    /**
    * Exclude dark and black colors below this value
    *
    * @var int
    */
    public $excludeBlack = 5;

    /**
    * Constructor
    *
    * @param string $file File path
    * @return Image
    */
    public function __construct($file)
    {
        $this->file = $file;
        $this->extension = substr($file, strrpos($file, '.') + 1);
        $this->info = getimagesize($file);

        $this->create();
    }

    /**
    * Destroy the image resource / close the file
    *
    */
    public function close()
    {
        imagedestroy($this->resource);
    }

    /**
    * Get image type if it is one of: .gif, .jpg or .png
    *
    * @param string $file Full path to file
    * @return string|boolean
    */
    public static function type($file)
    {
        $type = getimagesize($file);
        $type = $type[2];
        switch ($type) {
            case IMAGETYPE_GIF:     return 'gif';
            case IMAGETYPE_JPEG:    return 'jpg';
            case IMAGETYPE_PNG:     return 'png';
        }
        return false;
    }

    /**
    * Returns an integer representation of a color
    *
    * @param int $r Red
    * @param int $g Green
    * @param int $b Blue
    * @param int $a Alpha
    * @return int
    */
    public static function color($r, $g, $b, $a = 0)
    {
        return ($a << 24) + ($r << 16) + ($g << 8) + $b;
    }

    /**
    * Get color of a pixel
    *
    * @param int $x X coordinate
    * @param int $y Y coordinate
    * @return int
    */
    public function colorXY($x, $y)
    {
        return imagecolorat($this->resource, $x, $y);
    }

    /**
    * Returns RGB array of pixel's color
    *
    * @param int $x
    * @param int $y
    */
    public function rgbXY($x, $y)
    {
        $color = $this->colorXY($x, $y);
        return array(($color >> 16) & 0xFF, ($color >> 8) & 0xFF, $color & 0xFF);
    }

    /**
    * Create an image resource
    *
    */
    public function create()
    {
        switch ($this->info[2]) {
            case IMAGETYPE_JPEG:
                $this->resource = imagecreatefromjpeg($this->file);
                break;
            case IMAGETYPE_GIF:
                $this->resource = imagecreatefromgif($this->file);
                break;
            case IMAGETYPE_PNG:
                $this->resource = imagecreatefrompng($this->file);
                break;
            default:
                throw new Exception('Image type is not supported');
                break;
        }
    }

    /**
    * Get image width
    *
    * @return int Image width
    */
    public function width()
    {
        return imagesx($this->resource);
    }

    /**
    * Get image heights
    *
    * @return int Image height
    */
    public function height()
    {
        return imagesy($this->resource);
    }

    /**
    * Save image to file
    *
    * @param string $file File path
    * @param int $type Image type constant
    * @param int $quality JPEG compression quality from 0 to 100
    * @param int $permissions Unix file permissions
    */
    public function save(
        $file,
        $type = IMAGETYPE_JPEG,
        $quality = 75,
        $permissions = false
    ) {

        // create directory if necessary
        $dir = dirname($file);
        if (!file_exists($dir)) {
            $mask = umask();
            mkdir($dir, 0777, true);
            umask($mask);
        }

        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($this->resource, $file, $quality);
                break;
            case IMAGETYPE_GIF:
                imagegif($this->resource, $file);
                break;
            case IMAGETYPE_PNG:
                imagepng($this->resource, $file);
                break;
            default:
                throw new Exception('Image type is not supported');
                break;
        }

        // change image rights
        if ($permissions !== false) {
            chmod($file, $permissions);
        }

        // for method chaining
        return $this;
    }

    /**
    * Crop image
    *
    * @param int $x
    * @param int $y
    * @param int $w
    * @param int $h
    * @return Image
    */
    public function crop($x, $y, $w, $h)
    {
        $new = @imagecreatetruecolor($w, $h);

        // This needed to deal with .png transparency
        imagealphablending($new, false);
        imagesavealpha($new, true);
        $transparent = imagecolorallocatealpha($new, 255, 255, 255, 127);
        imagefilledrectangle($new, 0, 0, $w, $h, $transparent);

        if ($new === false) {
            throw new Exception('Cannot Initialize new GD image stream');
            return;
        }
        imagecopyresampled($new, $this->resource, 0, 0, $x, $y, $w, $h, $w, $h);
        $this->resource = $new;

        // for method chaining
        return $this;
    }

    /**
    * Resize image
    *
    * @param int $width New width
    * @param int $height New height
    */
    public function resize($width, $height)
    {
        $new = @imagecreatetruecolor($width, $height);

        // This needed to deal with .png transparency
        imagealphablending($new, false);
        imagesavealpha($new, true);
        $transparent = imagecolorallocatealpha($new, 255, 255, 255, 127);
        imagefilledrectangle($new, 0, 0, $width, $height, $transparent);

        imagecopyresampled(

            $new,

            $this->resource,

            0,

            0,

            0,

            0,

            $width,

            $height,
            $this->width(),

            $this->height()

        );
        $this->resource = $new;

        // for method chaining
        return $this;
    }

    /**
    * Fit the image with the same proportion into an area
    *
    * @param int $max_width
    * @param int $max_height
    * @param int $min_width
    * @param int $min_height
    * @return Image
    */
    public function fitResize($max_width = 150, $max_height = 150, $min_width = 20, $min_height = 20)
    {
        $kw = $max_width / $this->width();
        $kh = $max_height / $this->height();
        if ($kw > $kh) {
            $new_h = $max_height;
            $new_w = round($kh * $this->width());
        } else {
            $new_w = $max_width;
            $new_h = round($kw * $this->height());
        }
        $this->resize($new_w, $new_h);

        // Method chaining
        return $this;
    }

    /**
    * Resize image correctly scaled and than crop
    * the necessary area
    *
    * @param int $width New width
    * @param int $height New height
    */
    public function scaleResize($width, $height)
    {

        // calculate source coordinates
        $kw = $this->width() / $width;
        $kh = $this->height() / $height;
        if ($kh < $kw) {
            $src_h = $this->height();
            $src_y = 0;
            $src_w = round($kh * $width);
            $src_x = round(($this->width() - $src_w) / 2);
        } else {
            $src_h = round($kh * $height);
            $src_y = round(($this->height() - $src_h) / 2);
            $src_w = $this->width();
            $src_x = 0;
        }

        // copy new image
        $new = imagecreatetruecolor($width, $height);
        imagecopyresampled(
            $new,
            $this->resource,
            0,
            0,
            $src_x,
            $src_y,
            $width,
            $height,
            $src_w,
            $src_h
        );
        $this->resource = $new;

        // for method chaining
        return $this;
    }

    public function quantifyYCbCr()
    {

        // Init some vars
        $inc = $this->iteratorIncrement;
        $width = $this->width();
        $height = $this->height();
        list($Cb1, $Cb2, $Cr1, $Cr2) = $this->boundsCbCr;
        $white = $this->excludeWhite;
        $black = $this->excludeBlack;
        $total = $count = 0;

        for ($x = 0; $x < $width; $x += $inc) {
            for ($y = 0; $y < $height; $y += $inc) {
                list($r, $g, $b) = $this->rgbXY($x, $y);

                // Exclude white/black colors from calculation, presumably background
                if ((($r > $white) && ($g > $white) && ($b > $white)) ||
                    (($r < $black) && ($g < $black) && ($b < $black))) {
                    continue;
                }

                // Converg pixel RGB color to YCbCr, coefficients already divided by 255
                $Cb = 128 + (-0.1482 * $r) + (-0.291 * $g) + (0.4392 * $b);
                $Cr = 128 + (0.4392 * $r) + (-0.3678 * $g) + (-0.0714 * $b);

                // Increase counter, if necessary
                if (($Cb >= $Cb1) && ($Cb <= $Cb2) && ($Cr >= $Cr1) && ($Cr <= $Cr2)) {
                    $count++;
                }
                $total++;
            }
        }

        return $count / $total;
    }
}
