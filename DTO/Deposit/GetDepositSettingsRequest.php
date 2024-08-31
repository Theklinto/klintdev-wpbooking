<?php

namespace KlintDev\WPBooking\DTO\Deposit;

use KlintDev\WPBooking\DTO\DTOBase;
use KlintDev\WPBooking\DTO\DTOPropertyAttribute;
use KlintDev\WPBooking\DTO\DTOPropertyType;
use KlintDev\WPBooking\DTO\DTOValidationTrait;

class GetDepositSettingsRequest extends DTOBase {
	#[DTOPropertyAttribute( self::DEPOSIT_FLAOT, DTOPropertyType::Float, null )]
	public const DEPOSIT_FLAOT = "Deposit";
	#[DTOPropertyAttribute( self::STRIPE_ID_STR, DTOPropertyType::String, null )]
	public const STRIPE_ID_STR = "StripeId";
	#[DTOPropertyAttribute( self::STRIPE_PRICE_ID, DTOPropertyType::String, null )]
	public const STRIPE_PRICE_ID = "StripePriceId";
}