<?php

namespace App\Classes;

use \Exception;

final class Config
{
    private static $instance = null;
    private static $container;
    private static $config = [
        'cyrillic' => false, // da li je aplikacija cirilicna
        'pagination' => [
            // Podesavanja za stranicenje
            'per_page' => 10,
            'page_span' => 4,
        ],
        'mail' => [ // podesavanja za slanje email-a
            'host' => 'mail.eeckg.rs',
            'username' => 'kure@eeckg.rs',
            'password' => 'vir5373plus!',
            'port' => 465, // 465 = ssl, 587 = tls
            'from' => 'kure@eeckg.rs',
            'from_name' => 'EEC zakazivanje',
        ],
    ];

    public static function instance($container)
    {
        if (!isset(static::$instance)) {
            static::$instance = new static($container);
        }
        return static::$instance;
    }

    private function __construct($container)
    {
        static::$container = $container;
    }

    private function __clone()
    {
    }

    private function __sleep()
    {
    }

    private function __wakeup()
    {
    }

    public static function getContainer($instance_name = null)
    {
        if ($instance_name === null) {
            return static::$container;
        }
        if (isset(static::$container[$instance_name])) {
            return static::$container[$instance_name];
        }
        throw new Exception("U kontejneru ne postoji instanca {$instance_name}");
    }

    public static function get(string $key = null, $default = null)
    {
        if ($key === null) {
            return static::$config;
        }
        if (!is_string($key) || empty($key)) {
            throw new Exception("Naziv konfiguracije nije ispravan");
        }
        $data = static::$config;
        if (strpos($key, '.') !== false) {
            $keys = explode('.', $key);
            foreach ($keys as $k) {
                if (!isset($data[$k])) {
                    return $default;
                }
                if (!is_array($data)) {
                    return $default;
                }
                $data = $data[$k];
            }
        } else {
            return isset($data[$key]) ? $data[$key] : $default;
        }
        return $data === null ? $default : $data;
    }
}
