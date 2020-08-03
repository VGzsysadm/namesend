<?php
namespace App\Service;

class KeyGuardian
{
    private function loadKey() : EncryptionKey
    {
        try
        {
            KeyFactory::loadEncryptionKey($this->params->get('private_key'));
        }
        catch(\Throwable $e)
        {
            $this->logger->emergency(
                'Unable to lod the encryption key!', array(
                'error' => $e->getMessage(),
            ));
            throw $e;
        }
    }
}
