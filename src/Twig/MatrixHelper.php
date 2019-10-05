<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MatrixHelper extends AbstractExtension
{

    public function getFunctions() 
    {
        return array(
            new TwigFunction('print_matrix', array($this, 'print_matrix')),
            new TwigFunction('print_array', array($this, 'print_array')),
        );
    }

    public function print_array($array, $symbol) {
        $length = count($array);
        echo $symbol.$array[0].$symbol;
        for($i = 1;$i < $length ; $i++)
        {
            echo ", ".$symbol.$array[$i].$symbol;
        }
    }
    

    public function print_matrix($array) {
        if ($array != NULL) {
            $length = count($array);
            echo '[';
            $this->print_array($array[0], "");
            echo ']';
            for($i = 1;$i < $length ; $i++)
            {
                echo ', [';
                $this->print_array($array[$i], "");
                echo ']';
            }
        }
    }
}