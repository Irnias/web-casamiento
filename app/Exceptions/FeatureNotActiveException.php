<?php

namespace App\Exceptions;

use Exception;

class FeatureNotActiveException extends Exception
{
    public static function forKey(string $key): self
    {
        return new self("El módulo '{$key}' no está activo para este evento.");
    }
}
