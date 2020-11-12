<?php
namespace App\Service;

class passwordGenerator
{
    public function password_generate() 
    {
        $str = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str), 0, 12);
    }
}