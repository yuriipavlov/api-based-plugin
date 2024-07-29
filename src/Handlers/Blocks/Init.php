<?php

namespace APIBasedPlugin\Handlers\Blocks;

defined('ABSPATH') || exit;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use APIBasedPlugin\Handlers\Errors\ErrorHandler;
use APIBasedPlugin\Helper\Config;
use APIBasedPlugin\Helper\NotFoundException;
use APIBasedPluginBlocks;
use Throwable;

/**
 * Register blocks functionality
 *
 * @package    API Based
 */
class Init
{
    /**
     * Add Gutenberg block category
     *
     * @param array $categories
     *
     * @return array
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws NotFoundException
     *
     * @see https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/#managing-block-categories
     */
    public static function loadBlocksCategories(array $categories): array
    {
        return array_merge(
            [
                [
                    'slug'  => Config::get('blocksCategorySlug'),
                    'title' => Config::get('blocksCategoryTitle'),
                ],
            ],
            $categories
        );
    }

    /**
     * Register all blocks in blocks directory
     *
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws Throwable
     */
    public static function loadBlocks(): void
    {
        if (!function_exists('register_block_type_from_metadata')) {
            return;
        }

        $blocks = glob(Config::get('blocksDir') . '*', GLOB_ONLYDIR);

        foreach ($blocks as $blockPath) {
            $blockName = basename($blockPath);

            // Exclude blocks with name starting with underscore or without block.json file
            if (str_starts_with($blockName, '_') || !file_exists($blockPath . '/block.json')) {
                continue;
            }

            // Assuming each block has a BlockRenderer class in the appropriate namespace
            $Block = 'APIBasedPluginBlocks\\' . $blockName . '\\Block';

            // Instantiate the block class
            try {
                new $Block($blockName);
            } catch (Throwable $throwable) {
                ErrorHandler::handleThrowable($throwable);
            }
        }
    }
}
