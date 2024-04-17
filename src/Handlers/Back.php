<?php

namespace APIBasedPlugin\Handlers;

defined('ABSPATH') || exit;

use APIBasedPlugin\Helper\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Back End handler
 *
 * @package    API Based
 */
class Back
{
    /**
     * Load assets in editor
     *
     * @return void
     *
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function enqueueBlockEditorAssets(): void
    {
    }
}
