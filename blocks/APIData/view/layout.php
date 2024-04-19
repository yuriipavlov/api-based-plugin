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
    <table>
        <tr>
            <?php foreach ($data['columns'] as $column) {
                if (isset($data['apiData']->data->headers[$column])) { ?>
                    <th><?php echo htmlspecialchars($data['apiData']->data->headers[$column]); ?> </th>
                <?php }
            } ?>
        </tr>

        <?php foreach ($data['apiData']->data->rows as $row) { ?>
            <tr>
                <?php $index = 0; ?>
                <?php foreach ($row as $colIndex => $colData) { ?>
                    <?php if (in_array($index, $data['columns'])) { ?>
                        <td>
                            <?php echo $colIndex === 'date' ? date('Y-m-d H:i:s', $colData) : $colData; ?>
                        </td>
                    <?php } ?>
                    <?php $index++; ?>
                <?php } ?>
            </tr>
        <?php } ?>
    </table>
</div>
