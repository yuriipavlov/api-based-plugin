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
    <?php if (!empty($data['text'])) { ?>
        <p><?php echo esc_html($data['text']); ?></p>
    <?php } ?>
</div>
