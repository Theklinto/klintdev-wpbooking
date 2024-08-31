<?php

namespace KlintDev\WPBooking\Controllers;

use Exception;
use KlintDev\WPBooking\Attributes\RouteAttribute;
use KlintDev\WPBooking\DTO\Deposit\UpdateDepositSettingsRequest;
use KlintDev\WPBooking\DTO\StripeSettings\UpdateStripeSettingsRequest;
use KlintDev\WPBooking\Interfaces\IController;
use KlintDev\WPBooking\Services\OptionService;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

#[RouteAttribute( SettingsController::CONTROLLER_PREFIX )]
class SettingsController extends ControllerBase implements IController {
	public const CONTROLLER_PREFIX = "settings";
	public const UPDATE_STRIPE_SETTINGS_ENDPOINT = "stripe/update";
	public const UPDATE_DEPOSIT_ENDPOINT = "deposit/update";


	#[RouteAttribute( self::UPDATE_STRIPE_SETTINGS_ENDPOINT, WP_REST_Server::EDITABLE, true )]
	public function updateStripeSettings( WP_REST_Request $request ): WP_REST_Response {
		try {

			$result = $request->get_json_params();
			$dto    = UpdateStripeSettingsRequest::dtoFromArray( $result );

			$previousKey    = OptionService::stripeApiKey();
			$newKey         = $dto->getPropertyValue( $dto::API_KEY );
			$previousSecret = OptionService::stripeApiSecret();
			$newSecret      = $dto->getPropertyValue( $dto::API_SECRET );

			$error = [];

			if ( $previousKey !== $newKey ) {
				$keyUpdated = OptionService::stripeApiKey( $newKey );
				if ( ! $keyUpdated ) {
					$error[] = "Stripe API Key kunne ikke opdateres!";
				}
			}
			if ( $previousSecret !== $newSecret ) {
				$secretUpdated = OptionService::stripeApiSecret( $newSecret );
				if ( ! $secretUpdated ) {
					$error[] = "Stripe API Secret kunne ikke opdateres!";
				}
			}

			if ( count( $error ) > 0 ) {
				return new WP_REST_Response( join( "\n", $error ), 500 );
			}

			return new WP_REST_Response( [] );
		} catch ( Exception $exception ) {
			return new WP_REST_Response( $exception->getMessage(), 500 );
		}
	}

	#[RouteAttribute( self::UPDATE_DEPOSIT_ENDPOINT, WP_REST_Server::EDITABLE, true )]
	public function updateDeposit( WP_REST_Request $request ): WP_REST_Response {
		try {
			$params = $request->get_json_params();
			$dto    = UpdateDepositSettingsRequest::dtoFromArray( $params );

			OptionService::updateDepositSettings( $dto );

			return new WP_REST_Response( [], 200 );
		} catch ( Exception $exception ) {
			return new WP_REST_Response( $exception->getMessage(), 500 );
		}
	}

	public static function getEndpointUrl( string $method, array $queryParams = [] ): string {
		return self::baseGetEndpointUrl( self::CONTROLLER_PREFIX, $method );
	}
}