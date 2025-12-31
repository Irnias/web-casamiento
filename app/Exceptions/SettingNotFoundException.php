<?php

namespace App\Exceptions;

use Exception;

class SettingNotFoundException extends Exception
{
    public static function forEventAndKey(string $eventName, string $key): self
    {
        return new self("La configuración obligatoria '{$key}' no fue encontrada para el evento '{$eventName}'.");
    }
}
