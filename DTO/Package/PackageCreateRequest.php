<?php

namespace KlintDev\WPBooking\DTO\Package;

use AllowDynamicProperties;
use Exception;
use KlintDev\WPBooking\DTO\DTOBase;
use KlintDev\WPBooking\DTO\DTOPropertyAttribute;
use KlintDev\WPBooking\DTO\DTOPropertyType;
use KlintDev\WPBooking\DTO\DTOValidationTrait;

#[AllowDynamicProperties]
class PackageCreateRequest extends DTOBase {
	use DTOValidationTrait;

	#[DTOPropertyAttribute( self::ACTIVE_BOOL, DTOPropertyType::Bool, false )]
	public const ACTIVE_BOOL = "Active";
	#[DTOPropertyAttribute( self::ROOM_ID_INT, DTOPropertyType::Int, null )]
	public const ROOM_ID_INT = "RoomId";
	#[DTOPropertyAttribute( self::NAME_STR, DTOPropertyType::String, "" )]
	public const NAME_STR = "Name";
	#[DTOPropertyAttribute( self::PRICE_FLOAT, DTOPropertyType::Float, null )]
	public const PRICE_FLOAT = "Price";
	#[DTOPropertyAttribute( self::DEPOSIT_FLOAT, DTOPropertyType::Float, null )]
	public const DEPOSIT_FLOAT = "Deposit";
	#[DTOPropertyAttribute( self::START_TIME_STR, DTOPropertyType::String, null )]
	public const START_TIME_STR = "StartTime";
	#[DTOPropertyAttribute( self::DURATION_IN_HOURS_INT, DTOPropertyType::Int, null )]
	public const DURATION_IN_HOURS_INT = "DurationInHours";
	#[DTOPropertyAttribute( self::MONDAY_BOOL, DTOPropertyType::Bool, false )]
	public const MONDAY_BOOL = "Monday";
	#[DTOPropertyAttribute( self::TUESDAY_BOOL, DTOPropertyType::Bool, false )]
	public const TUESDAY_BOOL = "Tuesday";
	#[DTOPropertyAttribute( self::WEDNESDAY_BOOL, DTOPropertyType::Bool, false )]
	public const WEDNESDAY_BOOL = "Wednesday";
	#[DTOPropertyAttribute( self::THURSDAY_BOOL, DTOPropertyType::Bool, false )]
	public const THURSDAY_BOOL = "Thursday";
	#[DTOPropertyAttribute( self::FRIDAY_BOOL, DTOPropertyType::Bool, false )]
	public const FRIDAY_BOOL = "Friday";
	#[DTOPropertyAttribute( self::SATURDAY_BOOL, DTOPropertyType::Bool, false )]
	public const SATURDAY_BOOL = "Saturday";
	#[DTOPropertyAttribute( self::SUNDURDAY_BOOL, DTOPropertyType::Bool, false )]
	public const SUNDURDAY_BOOL = "Sunday";

	/**
	 * @throws Exception
	 */
	public function validate(): void {
		$this->validateString(
			self::NAME_STR,
			"Navnet skal være mellem 3 og 250 tegn",
			3,
			250
		);
		$this->validateInteger(
			self::DURATION_IN_HOURS_INT,
			"Udlejningslængden skal være mindst 1 time",
			1
		);
		$this->validateStringRegex(
			self::START_TIME_STR,
			"Tidspunktet skal være mellem 00:00 og 23:59",
			self::TIMESTAMP_REGEX
		);
		$this->validateInteger(
			self::ROOM_ID_INT,
			"Der skal være valgt et lokale",
			1
		);
	}
}