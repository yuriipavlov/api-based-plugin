<?php

namespace APIBasedPlugin\Helper;

defined('ABSPATH') || exit;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use APIBasedPlugin\App;

/**
 * Theme configuration helper
 *
 * @package API Based
 *
 */
class Config
{
    /**
     * Get config value by key
     *
     * @param string $key
     *
     * @return mixed
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws NotFoundException
     */
    public static function get(string $key): mixed
    {
        $parts = explode('/', $key);

        $config = App::container()->get('config');

        if (! isset($config[$parts[0]])) {
            throw new NotFoundException("No entry found for '$key'");
        }

        $value = $config[array_shift($parts)];

        foreach ($parts as $part) {
            if (is_array($value) && isset($value[$part])) {
                $value = $value[$part];
            } else {
                throw new NotFoundException("No entry found for '$key'");
            }
        }

        return $value;
    }
}
