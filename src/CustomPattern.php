<?php

class CustomPattern
{
	//private variables
	private $width             =  1200;
	private $height            =  900;
	private $color_filter      =  '#0000ff';
	private $pattern           =  '';

    private $list_patterns     =  array();
    private $path_of_pattern   =  '';

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function setFilterColor($color)
    {
        if(preg_match('/^#[a-f0-9]{6}$/i', $color)) //hex color is valid
        {
            $this->color_filter = $color;
            return $this;
        } else { 
            throw new Exception("Error: Hex color not valid!");
        }
    }

    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this->pattern;
    }

    public function setListPatterns($list)
    {
        $this->list_patterns = $list;
        return $this->list_patterns;
    }

    public function setPathPatterns($path)
    {
        $this->path_of_pattern = $path;
        return $this->path_of_pattern;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getFilterColor()
    {
        return $this->color_filter;
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function getListPatterns()
    {
        return $this->list_patterns;
    }

    public function getPathPattern()
    {
        return $this->path_of_pattern;
    }

    /**
     * @param      array      $options  Options
     *                          - width (int)
     *                          - height (int)
     *                          - color (#hexacode)
     *                          - pattern (string)
     *
     */
    function __construct($options = array())
    {
        // Set width if provided. If not, set default.
        if (isset($options['width'])) {
            $this->setWidth($options['width']);
        } else {
            $this->setWidth($this->width);
        }

        // Set height if provided. If not, set default.
        if (isset($options['height'])) {
            $this->setHeight($options['height']);
        } else {
            $this->setHeight($this->height);
        }

        // Set filter color if provided.
        if (isset($options['color'])) {
            $this->setFilterColor($options['color']);
        } else { //default color is blue
        	$this->setFilterColor($this->color_filter);
        }

        $current_directory = dirname(__FILE__);
        $patterns_directory = $current_directory . '/img_patterns';
        $path_list  = glob($patterns_directory.'/*.jpg');
        for($i=0; $i<count($path_list); $i++)
        {
            $list[$i] = pathinfo($path_list[$i], PATHINFO_FILENAME);
        }

        $this->setListPatterns($list);
        // Set pattern if provided.
        if (isset($options['pattern'])) 
        {
            if(in_array($options['pattern'], $this->getListPatterns() )) {
                $this->setPattern($options['pattern']);
                $this->setPathPatterns($patterns_directory.'/'.$this->getPattern().'.jpg');
            } else {
                throw new Exception("Error: Pattern ".$options['pattern']." doesn't exist", 1);
            }
    		
        } else { //Set a random pattern
            $file = array_rand($list);      
            $random_file = $list[$file];    
            $this->setPattern($random_file);
        }
    }

	/**
     * Creates a pattern.
     *
     * @param      string     $output     The output
     * @param      string     $save_path  The save path
     *
     * @throws     Exception  (must specify the output value)
     *
     * @return     resource   $im         The image resource
     */
    public function createPattern($output, $save_path = NULL)
    {
		//Create big canvas
		$im  = imagecreatetruecolor($this->getWidth(), $this->getHeight());
		$bgc = imagecolorallocate($im, 255, 255, 255); //white
		imagefilledrectangle($im, 0, 0, $this->getWidth(), $this->getHeight(), $bgc);

        //Create image and apply filters
		$image = imagecreatefromjpeg($this->getPathPattern());
		imagefilter($image, IMG_FILTER_BRIGHTNESS, -70);
		$color = $this->hex2rgb($this->getFilterColor() );
		imagefilter($image, IMG_FILTER_COLORIZE, $color[0], $color[1], $color[2]);

		//Paste image on bigger canvas
		$size_pattern = getimagesize($this->getPathPattern());
		for($i=0; $i<(imagesx($im)/$size_pattern[0]); $i++)
		{
	        for($j=0; $j<(imagesy($im)/$size_pattern[1]); $j++)
	        {
	               imagecopyresampled($im, $image, $size_pattern[0]*$i, $size_pattern[1]*$j, 0, 0, $size_pattern[0], $size_pattern[1], $size_pattern[0], $size_pattern[1]);    
	        }  
		}

		if ($output == 'browser' || $output == 'save') {
            header('Content-Type: image/jpeg');
            imagejpeg($im, $save_path);
            die();
        }

		else if ($output == 'phpgd') {
			return $im;
        }

        else {
            throw new Exception("Output ".$output." is incorrect. Please specify one of the following: 'browser', 'save' or 'phpgd'. ");
        }
    }

    /**
     * Converts hexadecimal color to rgb color
     *
     * @param      string       $c      hexadecimal value
     *
     * @return     array                rgb value
     */
    private function hex2rgb($c)
    {
        $c = str_replace("#", "", $c);
        if(strlen($c) == 3) { 
            $r = hexdec( $c[0] . $c[1] );
            $g = hexdec( $c[1] . $c[1] );
            $b = hexdec( $c[2] . $c[1] );
        } elseif (strlen($c) == 6 ) {
            $r = hexdec( $c[0] . $c[2] );
            $g = hexdec( $c[2] . $c[2] );
            $b = hexdec( $c[4] . $c[2] );
        } else {
            $r = 'ff';
            $g = 'ff';
            $b = '00';
        }

        return array($r, $g, $b);
    }
}
?>