<?php

/**
 * Block view template
 *
 * @var $data array
 */

defined('ABSPATH') || exit;

$data = $data ?? [];

?>

<div class="api-data-block">
    <?php var_dump( $data['apiData'] ?? ''); ?>
</div>
