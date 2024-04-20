<?php

namespace APIBasedPlugin\Handlers\CLI\Commands;

defined('ABSPATH') || exit;

/**
 * Interface for CLI commands
 *
 * @package    API Based
 */
interface CLICommandInterface
{
    /**
     * CLI command run function
     * @return void
     */
    public static function run(): void;
}
