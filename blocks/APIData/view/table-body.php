<?php

/**
 * Block view template
 *
 * @var $data array
 */

defined('ABSPATH') || exit;

$data = $data ?? [];

?>

<?php if (!empty($data['apiData'])) { ?>
    <?php foreach ($data['apiData'] as $key => $row) { ?>
        <tr>
            <?php foreach ($row as $colIndex => $colData) { ?>
                <td>
                    <?php echo esc_html($colData); ?>
                </td>
            <?php } ?>
        </tr>
    <?php } ?>
<?php } ?>
