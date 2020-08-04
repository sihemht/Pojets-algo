#!/bin/bash
<?php
class Calcul
{
    const OPERATEUR = '/(?:\-?\d+(?:\.?\d+)?[\+\-\%\*\/])+\-?\d+(?:\.?\d+)?/';
  // const PARENTHESIS_DEPTH = 10;

    public function Myeval(string $expr)
    {
        if (strpos($expr, '+') != null || strpos($expr, '-') != null || strpos($expr, '/') != null || strpos($expr, '*') != null || strpos($expr, '%') != null) {

            //Remove white spaces and invalid math chars
            $expr = str_replace(',', '.', $expr);
            $expr = preg_replace('[^0-9\.\+\-\*\/\(\)]', '', $expr);


            //Calculate each of the parenthesis from the top
            $i = 0;
            while (strpos($expr, '(') || strpos($expr, ')')) {
                $expr = preg_replace_callback('/\(([^\(\)]+)\)/', 'self::callback', $expr);
                $i++;
             /*    if ($i > self::PARENTHESIS_DEPTH) {
                    break;
                } */
            }
            //  Calculate the result
            if (preg_match(self::OPERATEUR, $expr, $match)) {
                return $this->compute($match[0]);
            }

            // To handle the special case of expressions surrounded by global parenthesis like "(1+1)"
            if (is_numeric($expr)) {
                return $expr;
                var_dump($expr);
            }

            return 0;
        }

        return $expr;
    }

    private function compute($expr)
    {
        $compute = create_function('', 'return ' . $expr . ';');
        return 0 + $compute();
    }
    private function callback($expr)
    {
        if (is_numeric($expr[1])) {
            return $expr[1];
        } elseif (preg_match(self::OPERATEUR, $expr[1], $match)) {
            return $this->compute($match[0]);
        }

        return 0;
    }
}
$call = new Calcul();
$result = $call->Myeval("10/0");
echo $result .PHP_EOL;
