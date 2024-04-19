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
    <?php if (!empty($data['apiData']->title)) { ?>
        <h2><?php echo esc_html($data['apiData']->title); ?></h2>
    <?php } ?>
    <table>
        <tr>
            <?php foreach ($data['columns'] as $column) {
                if (isset($data['apiData']->data->headers[$column])) { ?>
                    <th><?php echo esc_html($data['apiData']->data->headers[$column]); ?> </th>
                <?php }
            } ?>
        </tr>

        <?php if (!empty($data['apiData']->data->rows)) { ?>
            <?php foreach ($data['apiData']->data->rows as $row) { ?>
                <tr>
                    <?php $index = 0; ?>
                    <?php foreach ($row as $colIndex => $colData) { ?>
                        <?php if (in_array($index, $data['columns'])) { ?>
                            <td>
                                <?php if ($colIndex === 'date') {
                                    echo date('Y-m-d H:i:s', esc_html($colData));
                                } else {
                                    echo esc_html($colData);
                                } ?>
                            </td>
                        <?php } ?>
                        <?php $index++; ?>
                    <?php } ?>
                </tr>
            <?php } ?>
        <?php } ?>
    </table>
</div>
