<?php

/**
 * Block view template
 *
 * @var $data array
 */

defined('ABSPATH') || exit;

$data = $data ?? [];

?>

<div class="api-data-inner">
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col"><?php esc_html_e('ID', 'api-based-plugin') ?></th>
                <th scope="col"><?php esc_html_e('First Name', 'api-based-plugin') ?></th>
                <th scope="col"><?php esc_html_e('Last Name', 'api-based-plugin') ?></th>
                <th scope="col"><?php esc_html_e('Email', 'api-based-plugin') ?></th>
                <th scope="col"><?php esc_html_e('Date', 'api-based-plugin') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($data['apiData'])) {
            $this->loadBlockView(
                'table-body',
                [
                    'apiData' => $data['apiData'],
                ],
                null,
                true
            );
        } ?>
        </tbody>
    </table>
</div>
