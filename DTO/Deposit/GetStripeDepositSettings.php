<?php

namespace KlintDev\WPBooking\DTO\Deposit;

use KlintDev\WPBooking\DTO\DTOBase;
use KlintDev\WPBooking\DTO\DTOPropertyAttribute;
use KlintDev\WPBooking\DTO\DTOPropertyType;

class GetStripeDepositSettings extends DTOBase{
	#[DTOPropertyAttribute(self::DEPOSIT_PRODUCT_NAME_STR, DTOPropertyType::String, "")]
	public const DEPOSIT_PRODUCT_NAME_STR = "ProductName";
	#[DTOPropertyAttribute(self::DEPOSIT_PRICE_FLOAT, DTOPropertyType::Float, 0)]
	public const DEPOSIT_PRICE_FLOAT = "DepositPriceFloat";
}