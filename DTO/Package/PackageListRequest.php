<?php

namespace KlintDev\WPBooking\DTO\Package;

use AllowDynamicProperties;
use KlintDev\WPBooking\DTO\DTOBase;
use KlintDev\WPBooking\DTO\DTOPropertyAttribute;
use KlintDev\WPBooking\DTO\DTOPropertyType;

#[AllowDynamicProperties]
class PackageListRequest extends DTOBase {
	#[DTOPropertyAttribute( self::ID_INT, DTOPropertyType::Int, null )]
	public const ID_INT = "Id";
	#[DTOPropertyAttribute( self::ACTIVE_BOOL, DTOPropertyType::Bool, false )]
	public const ACTIVE_BOOL = "Active";
	#[DTOPropertyAttribute( self::ROOM_NAME, DTOPropertyType::String, null )]
	public const ROOM_NAME = "RoomName";
	#[DTOPropertyAttribute( self::NAME_STR, DTOPropertyType::String, "" )]
	public const NAME_STR = "Name";
	#[DTOPropertyAttribute( self::PRICE_FLOAT, DTOPropertyType::Float, null )]
	public const PRICE_FLOAT = "Price";
	#[DTOPropertyAttribute( self::DURATION_IN_HOURS_INT, DTOPropertyType::Int, null )]
	public const DURATION_IN_HOURS_INT = "DurationInHours";
}