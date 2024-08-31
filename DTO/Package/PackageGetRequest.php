<?php

namespace KlintDev\WPBooking\DTO\Package;

use AllowDynamicProperties;
use KlintDev\WPBooking\DTO\DTOBase;
use KlintDev\WPBooking\DTO\DTOPropertyAttribute;
use KlintDev\WPBooking\DTO\DTOPropertyType;

#[AllowDynamicProperties]
class PackageGetRequest extends DTOBase{
	#[DTOPropertyAttribute( self::ID_INT, DTOPropertyType::Int, null )]
	public const ID_INT = "Id";
	#[DTOPropertyAttribute( self::ACTIVE_BOOL, DTOPropertyType::Bool, false )]
	public const ACTIVE_BOOL = "Active";
	#[DTOPropertyAttribute( self::ROOM_ID_INT, DTOPropertyType::Int, null )]
	public const ROOM_ID_INT = "RoomId";
	#[DTOPropertyAttribute( self::NAME_STR, DTOPropertyType::String, "" )]
	public const NAME_STR = "Name";
	#[DTOPropertyAttribute( self::PRICE_FLOAT, DTOPropertyType::Float, null )]
	public const PRICE_FLOAT = "Price";
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
}