<?php

namespace APIBasedPlugin\Handlers\Blocks;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use APIBasedPlugin\Helper\NotFoundException;

interface BlockInterface
{
    /**
     * Register block additional arguments including server side render callback
     * $this->blockArgs['render_callback'] = [$this, 'blockServerSideCallback'];
     *
     * @return void
     */
    public function registerBlockArgs(): void;

    /**
     * Register rest api endpoints
     * Runs by abstract constructor
     *
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    public function blockRestApiEndpoints(): void;
}
