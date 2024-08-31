<?php

namespace KlintDev\WPBooking\Components;

class Table {
	public static function noRowsText(
		string $message,
		array $arr
	): string|false {
		ob_start();

		if ( count( $arr ) > 0 ) {
			return "";
		}

		?>
        <tr>
            <td colspan="100" style="text-align: center">
                                    <span class="text-secondary">
                                    <?= $message ?>
                                    </span>
            </td>
        </tr>
		<?php return ob_get_clean();
	}

	public static function editButton(
		string $href
	): string|false {
		ob_start(); ?>
        <td class="table-edit-btn">
            <a href="<?= $href ?>" class="btn btn-outline-primary">
                <span class="dashicons dashicons-edit-large"></span>
            </a>
        </td>
		<?php return ob_get_clean();
	}
}