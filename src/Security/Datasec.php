<?php

namespace App\Security;

require __DIR__ . '../../../vendor/autoload.php';

use phpseclib\Crypt\RSA;

class Datasec
{
    private $public_key;
    private $private_key;

    public function __construct($public_key, $private_key)
    {
        $this->public_key = $public_key;
        $this->private_key = $private_key;
    }

    public function getPublic()
    {
        return $this->public_key;
    }

    public function getPrivate()
    {
        return $this->private_key;
    }

    function encrypt($message) {
        $public_key = file_get_contents($this->getPublic());
        $rsa = new RSA();
        $rsa->loadKey($public_key);
        $rsa->setEncryptionMode(RSA::ENCRYPTION_OAEP);
        $ciphertext = $rsa->encrypt($message);
        return base64_encode($ciphertext);
    }
    function decrypt($ciphertext) {
        $private_key = file_get_contents($this->getPrivate());
        $rsa = new RSA();
        $rsa->loadKey($private_key);
        $rsa->setEncryptionMode(RSA::ENCRYPTION_OAEP);
        $message = $rsa->decrypt(base64_decode($ciphertext));
        return $message;
    }
}