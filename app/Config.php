<?php

namespace App;

use App\Interfaces\Singleton;

class Config extends Singleton
{
    private $config;

    protected function __construct()
    {
        $this->setConfig();
    }

    private function setConfig()
    {
        if (!file_exists('config.php')) {
            throw new \Exception('File config not found');
        }

        $this->config = include "config.php";
    }

    public function get(string $key)
    {
        if (strpos($key, '.') === false) {
            return $this->config[$key] ?? null;
        }

        $pathKeys = explode('.', $key);

        $data = $this->config[array_shift($pathKeys)] ?? [];
        foreach ($pathKeys as $key) {
            if (isset($data[$key])) {
                $data = $data[$key];
            }
        }

        return $data;
    }
}