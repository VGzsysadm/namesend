<?php
namespace App\Service;

class Randomize
{
    public function randomize()
    {
        $bytestring = random_bytes(64);
        return bin2hex($bytestring);
    }
}
