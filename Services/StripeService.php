<?php

namespace KlintDev\WPBooking\Services;

use Exception;
use KlintDev\WPBooking\DTO\Deposit\GetStripeDepositSettings;
use KlintDev\WPBooking\DTO\Deposit\UpdateDepositSettingsRequest;
use KlintDev\WPBooking\DTO\DTOBase;
use KlintDev\WPBooking\Logging\Logger;
use ReflectionException;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripeService {
	/**
	 * @throws ReflectionException
	 * @throws Exception
	 */
	public static function updateDepositProduct( UpdateDepositSettingsRequest $request ): UpdateDepositSettingsRequest {
		$stripeKey = OptionService::stripeApiSecret();
		$client    = new StripeClient( $stripeKey );

		// Descriptes the price without decimal in cents as a int
		$priceInCents = ( $request->getPropertyValue( UpdateDepositSettingsRequest::DEPOSIT_FLAOT ) * 100 );

		$productCreated = false;
		if ( empty( $request->getPropertyValue( UpdateDepositSettingsRequest::STRIPE_ID_STR ) ) ) {

			$params = [
				"name"   => "Depositum",
				"active" => true,
			];
			try {
				$product = $client->products->create( $params );
			} catch ( Exception $e ) {
				Logger::log_error( "Failed to create deposit product", [
					"Exception" => $e->getMessage(),
					...$params
				] );
				throw new Exception( 'Kunne ikke oprette depositum hos STRIPE. Se loggen for mere info.' );
			}
			$request->assignValue(
				UpdateDepositSettingsRequest::STRIPE_ID_STR,
				$product->id
			);
			$productCreated = true;

		} else {
			$priceId = $request->getPropertyValue( UpdateDepositSettingsRequest::STRIPE_PRICE_ID );
			$params  = [
				"active" => false,
			];
			try {
				$client->prices->update( $priceId, $params );

			} catch ( Exception $e ) {
				Logger::log_error( "Failed to deactive old deposit product", [
					"Exception" => $e->getMessage(),
					"PriceId"   => $priceId,
					...$params
				] );
				throw new Exception( 'Kunne ikke deaktivere det gamle depositum hos STRIPE. Se loggen for mere info.' );
			}
		}

		$productId = $request->getPropertyValue( UpdateDepositSettingsRequest::STRIPE_ID_STR );

		$params = [
			"active"      => true,
			"product"     => $productId,
			"currency"    => "DKK",
			"unit_amount" => $priceInCents,
		];
		try {

			$price = $client->prices->create( $params );
			$request->assignValue(
				UpdateDepositSettingsRequest::STRIPE_PRICE_ID,
				$price->id
			);
		} catch ( Exception $e ) {
			Logger::log_error( "Failed to create new deposit product", [
				"Exception" => $e->getMessage(),
				...$params
			] );

			if ( $productCreated ) {
				try {
					$client->products->delete( $productId );
				} catch ( Exception $e ) {
					Logger::log_error( "Failed to delete newly created deposit product, after error", [
						"Exception" => $e->getMessage(),
						"ProductId" => $productId,
					] );
				}
			}
			throw new Exception( "Der skete en fejl ved opdatering af depositum prisen hos STRIPE. Se loggen for mere info." );
		}

		return $request;
	}

	/**
	 * @throws ReflectionException
	 */
	public static function getStripeDepositSettings(): GetStripeDepositSettings|null {
		$depositOptions = OptionService::getDepositSettings();
		$secret         = OptionService::stripeApiSecret();


		$productId = $depositOptions->getPropertyValue( UpdateDepositSettingsRequest::STRIPE_ID_STR );
		$priceId   = $depositOptions->getPropertyValue( UpdateDepositSettingsRequest::STRIPE_PRICE_ID );

		if ( empty( $productId ) && empty( $priceId ) ) {
			return null;
		}

		$client      = new StripeClient( $secret );
		$productName = "";
		try {
			$product     = $client->products->retrieve( $productId );
			$productName = $product->name;
		} catch ( Exception $e ) {
			Logger::log_error( "Failed to retrieve deposit product", [
				"Exception" => $e->getMessage(),
				"ProductId" => $productId,
			] );
			$productName = "Kunne ikke hente depositum produktet";
		}

		$depositPrice = 0;
		try {
			$price        = $client->prices->retrieve( $priceId );
			$depositPrice = ( $price->unit_amount / 100.00 );
		} catch ( Exception $e ) {
			Logger::log_error( "Failed to retrieve deposit price", [
				"Exception" => $e->getMessage(),
				"PriceId"   => $priceId,
			] );
		}

		return GetStripeDepositSettings::dtoFromArray( [
			GetStripeDepositSettings::DEPOSIT_PRODUCT_NAME_STR => $productName,
			GetStripeDepositSettings::DEPOSIT_PRICE_FLOAT      => $depositPrice,
		] );
	}
}