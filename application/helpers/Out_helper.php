<?php defined('BASEPATH') OR exit('No direct script access allowed');


if ( ! function_exists('out'))
{

    function out($array)
    {
        echo '<pre>';
        print_r($array);
        die;
    }
}
