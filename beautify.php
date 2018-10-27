<?php
class Debug {
    var $indent_size;
    var $colors = array(
        "Teal",
        "YellowGreen",
        "Tomato",
        "Navy",
        "MidnightBlue",
        "FireBrick",
        "DarkGreen"
        );

    function __construct() {
        $this->indent_size = '20';
    }

    /*
    *   Author: Phil Harmon
    *   Description:
    *       Take an array and format it to style in HTML
    *
    *   Tasks:
    *       - Add automated color formatting
    */
    function array_to_html($val) {
        $do_nothing = true;

        // Get string structure
        if(is_array($val)) {
            ob_start();
            print_r($val);
            $val = ob_get_contents();
            ob_end_clean();
        }

        // Color counter
        $current = 0;

        // Split the string into character array
        $array = preg_split('//', $val, -1, PREG_SPLIT_NO_EMPTY);
        foreach($array as $char) {
            if($char == "[")
                if(!$do_nothing)
                    echo "</div>";
                else $do_nothing = false;
            if($char == "[")
                echo "<div>";
            if($char == ")") {
                echo "</div></div>";
                $current--;
            }

            echo $char;

            if($char == "(") {
                echo "<div class='indent' style='padding-left: {$this->indent_size}px; color: ".($this->colors[$current % count($this->colors)]).";'>";
                $do_nothing = true;
                $current++;
            }
        }
    }
  }


 ?>
