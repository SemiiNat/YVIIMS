<?php

namespace App\Helper;

class EnvHelper
{
    public static function load()
    {
        $envFilePath = realpath(__DIR__ . '/../../.env');

        if (!file_exists($envFilePath)) {
            throw new \Exception('.env file does not exist');
        }

        $file = fopen($envFilePath, 'r');
        if ($file) {
            while (($line = fgets($file)) !== false) {
                if (preg_match('/\s*(\w+)\s*=\s*(.+)/', $line, $matches)) {
                    putenv("$matches[1]=$matches[2]");
                }
            }
            fclose($file);
        } else {
            throw new \Exception('Unable to read the .env file');
        }
    }

    public static function get($key, $default = null)
    {
        $value = getenv($key);
        return ($value === false) ? $default : $value;
    }
}
