<?php

namespace APIBasedPlugin\Handlers\Blocks;

defined('ABSPATH') || exit;

/**
 * Blocks interface for blocks controllers
 *
 * @package    API Based
 */
interface BlockInterface
{
    /**
     * Block server side endpoint
     *
     * @param $attributes
     * @param $content
     * @param $block
     *
     * @return string
     */
    public static function blockServerSideCallback($attributes, $content, $block): string;
}
