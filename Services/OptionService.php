<?php

namespace KlintDev\WPBooking\Services;

use Exception;
use KlintDev\WPBooking\DTO\Deposit\GetDepositSettingsRequest;
use KlintDev\WPBooking\DTO\Deposit\UpdateDepositSettingsRequest;
use KlintDev\WPBooking\GlobalSettings;
use KlintDev\WPBooking\Logging\Logger;
use ReflectionException;

class OptionService {
	protected const API_KEY_OPTION = GlobalSettings::PLUGIN_PREFIX . "_stripe_api_key";
	protected const API_SECRET_OPTION = GlobalSettings::PLUGIN_PREFIX . "_stripe_api_secret";
	protected const DEPOSIT_OPTION_SETTINGS = GlobalSettings::PLUGIN_PREFIX . "_deposit_settings";

	public static function stripeApiKey( ?string $value = null ): bool|string {
		if ( $value === null ) {
			return get_option( self::API_KEY_OPTION );
		} else {
			return update_option( self::API_KEY_OPTION, $value );
		}
	}

	public static function stripeApiSecret( ?string $value = null ): bool|string {
		if ( $value === null ) {
			return get_option( self::API_SECRET_OPTION );
		} else {
			return update_option( self::API_SECRET_OPTION, $value );
		}
	}

	/**
	 * @throws ReflectionException
	 */
	public static function getDepositSettings(): GetDepositSettingsRequest {
		$result = get_option( self::DEPOSIT_OPTION_SETTINGS );
		if ( $result === false || $result === null ) {
			return GetDepositSettingsRequest::createDTO();
		}

		if ( gettype( $result ) != "string" ) {
			$result = json_encode( $result );
		}
		$arr = json_decode( $result, true );

		return GetDepositSettingsRequest::dtoFromArray( $arr );
	}

	/**
	 * @throws Exception
	 */
	public static function updateDepositSettings(
		UpdateDepositSettingsRequest $request
	): void {
		$result       = get_option( self::DEPOSIT_OPTION_SETTINGS );
		$valuechanged = self::arrayValueChanged(
			json_decode( $result, true ),
			(array) $request
		);
		if ( $valuechanged ) {

			$mergedData     = $result !== null && $result !== false ?
				array_merge( json_decode( $result, true ), (array) $request ) :
				(array) $request;
			$updatedRequest = UpdateDepositSettingsRequest::dtoFromArray( $mergedData );

			StripeService::updateDepositProduct( $updatedRequest );

			$result = update_option( self::DEPOSIT_OPTION_SETTINGS, json_encode( $updatedRequest ) );
			if ( $result === false ) {
				Logger::log_error( "Deposit settings option could not be updated" );
				throw new Exception( "Depositum indstillinger blev ikke opdateret" );
			}
		}
	}

	protected static function arrayValueChanged(
		array $old,
		array $new
	): bool {
		foreach ( $new as $key => $value ) {
			if ( ! isset( $old[ $key ] ) || $old[ $key ] != $value ) {
				return true;
			}
		}

		return false;
	}
}