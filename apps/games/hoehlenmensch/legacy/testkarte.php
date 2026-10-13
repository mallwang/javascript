<?php
class MyMap
{
    private $map_width;         // Kartenbreite
    private $map_height;        // Kartenhöhe
    private $field_width_px;    // Breite eines Feldes in Pixel
    private $field_height_px;   // Höhe eines Feldes in Pixel
 
    private $window_width;      // Fensterbreite
    private $window_height;     // Fensterhöhe
    private $window_topleft_x;  // X-Koordinate oben/links
    private $window_topleft_y;  // Y-Koordinate oben/links
 
    private $fields;    // Kartenfelder
 
    private $x;        // X-Koordinate der aktuellen Position
    private $y;        // Y-Koordinate der aktuellen Position
 
    public function setFields($fields)
    {
        $this->fields = $fields;
    }
    public function setField($x, $y, $field)
    {
        if (($x < 0) || ($y < 0) || ($x >= $this->map_width) || ($y >= $this->map_height)) return false;
        $this->fields[$x][$y] = $field;
    }
    public function getField($x, $y)
    {
        if (@isset($this->fields[$x][$y])) return $this->fields[$x][$y]; else return false;
    }
 
    public function setSize($width, $height)
    {
        $this->map_width = intval($width);
        $this->map_height = intval($height);
    }
    public function setFieldSize($width_px, $height_px)
    {
        $this->field_width_px = intval($width_px);
        $this->field_height_px = intval($height_px);
    }
    public function setPosition($x, $y)
    {
        if ($x < 0) $x = 0;
        if ($y < 0) $y = 0;
        if ($x >= $this->map_width) $x = $this->map_width - 1;
        if ($y >= $this->map_height) $y = $this->map_height - 1;
        $this->x = $x;
        $this->y = $y;
        $this->setWindowPosition($x - ($this->window_width >> 1), $y - ($this->window_height >> 1));
    }
    public function setWindowSize($width, $height)
    {
        $this->window_width = intval($width);
        $this->window_height = intval($height);
    }
    public function setWindowPosition($x, $y)
    {
        if ($x < 0) $x = 0;
        if ($y < 0) $y = 0;
        if ($x+$this->window_width > $this->map_width) $x = $this->map_width - $this->window_width;
        if ($y+$this->window_height > $this->map_height) $y = $this->map_height - $this->window_height;
        $this->window_topleft_x = intval($x);
        $this->window_topleft_y = intval($y);
    }
    public function getWindowHtml()
    {
        $code = '<div id="mymapwindow">';
        $window_bottomright_x = ($this->window_topleft_x + ($this->window_width-1) );
        $window_bottomright_y = ($this->window_topleft_y + ($this->window_height-1) );
        for($y=$this->window_topleft_y; $y <= $window_bottomright_y; $y++)
        {
            for($x=$this->window_topleft_x; $x <= $window_bottomright_x; $x++)
            {
                $field = $this->getField($x, $y);
                if ($field)
                {
					$href = $field['href'];
					$src = $field['src'];
                    //$color = $field['color'];
                    $caption = $field['caption'];;
                }
                else
                {
                    $href = 'images/Karte_Haus.jpg';
					$src = 'images/Karte_leer.jpg';
                    //$color = '#009933';
                    $caption = 'leeres Land';
                };
                //code .= '<a href='.$href.'><div style="float: left; background-image:url('.$src.'); width: '.($this->field_width_px).'px; height: '.($this->field_height_px).'px; 
				//margin-right: 0px; margin-bottom: 0px; text-align: center;" title="'.$caption.'"></div>';
				};
            $code .= '<div style="clear:both;"></div>';
        };
        $code .= '</div>';
        return $code;
    }
    public function showWindow()
    {
        echo $this->getWindowHtml();
    }
    public function getHtml()
    {
        $code = '<div id="mymap">';
        for($y=0; $y < $this->map_height; $y++)
        {
            for($x=0; $x < $this->map_width; $x++)
            {
                $field = $this->getField($x, $y);
                if ($field)
                {
					$href = $field['href'];
					$src = $field['src'];
                    //$color = $field['color'];
                    $caption = $field['caption'];
                }
                else
                {
					$href = 'images/Karte_Haus.jpg';
					$src = 'images/Karte_leer.jpg';
                    //$color = '#009933';
                    $caption = 'leeres Land';
                };//style="background-image:url('.$src.')";
                $code .= '<a href='.$href.'><div style="float: left; background-image:url('.$src.'); width: '.($this->field_width_px).'px; height: '.($this->field_height_px).'px; 
				margin-right: 0px; margin-bottom: 0px; text-align: center;" title="'.$caption.'"></div>';
				//$code .= '<div style="float: left; width: '.($this->field_width_px-1).'px; height: '.($this->field_height_px-1).'px; 
				//margin-right: 1px; margin-bottom: 1px; src= '.$src.'; background-color: '.$color.'; text-align: center;" title=" '.$caption.' ">('.$x.'|'.$y.')</div>';
				//sonclick="location.href='http://www.example.com';"
            };
            $code .= '<div style="clear:both;"></div>';
        };
        $code .= '</div>';
        return $code;
    }
    public function show()
    {
        echo $this->getHtml();
    }
};

?>