<?php

namespace Src;

use Error;

class Settings
{
    private array $_settings;

    public function __construct(array $settings = [])
    {
        $this->_settings = $settings;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->_settings)) {
            return $this->_settings[$key];
        }
        throw new Error('Accessing a non-existent property');
    }

    public function getRootPath(): string
    {
        return isset($this->_settings['path']['root']) ? '/' . $this->_settings['path']['root'] : '';
    }

    public function getViewsPath(): string
    {
        return isset($this->_settings['path']['views']) ? '/' . $this->_settings['path']['views'] : '/views';
    }
    public function getDbSetting(): array
    {
    return $this->db ?? [];
    }

}