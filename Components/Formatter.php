<?php

namespace KlintDev\WPBooking\Components;

use DateTime;
use Exception;

class Formatter {
	public static function formatDate( string $date ): string {
		if ( strlen( $date ) == 0 || $date === '0000-00-00' ) {
			return '';
		}


		try {
			$date = new DateTime( $date );

			return $date->format( 'd-m-Y' );
		} catch ( Exception $e ) {
			return $e->getMessage();
		}
	}

	public static function formatBooleanIcon( bool $value ): string|false {
		ob_start(); ?>
        <span class="dashicons dashicons-<?= $value ? "yes" : "no-alt" ?> <?= $value ? "text-primary" : "text-secondary" ?>"></span>
		<?php return ob_get_clean();
	}

	public static function maxLengthText( string $text, int $maxLength ): string {
		if ( strlen( $text ) > $maxLength ) {
			return substr( $text, 0, $maxLength - 3 ) . "...";
		}

		return $text;
	}

	public static function formatCurrency( int|float $amount ): string {
		return self::formatNumber( $amount ) . "DKK";
	}

	public static function formatNumber( int|float $number ): string {
		return number_format( $number, 2, ',', '.' );
	}
}