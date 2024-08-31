<?php

namespace KlintDev\WPBooking\Entities;

use AllowDynamicProperties;
use KlintDev\WPBooking\Attributes\DBColumnAttribute;
use KlintDev\WPBooking\Attributes\DBTableAttribute;

#[AllowDynamicProperties]
#[DBTableAttribute( "package", 1 )]
class Package {
	#[DBColumnAttribute( self::ID_INT, DBColumnAttribute::INTEGER, "bigint unsigned", null, false, true )]
	public const ID_INT = "id";
	#[DBColumnAttribute( self::ACTIVE_BOOL, DBColumnAttribute::INTEGER, "tinyint(1)", null )]
	public const ACTIVE_BOOL = "active";
	#[DBColumnAttribute( self::ROOM_ID_INT, DBColumnAttribute::INTEGER, "bigint unsigned", null )]
	public const ROOM_ID_INT = "room_id";
	#[DBColumnAttribute( self::NAME_STR, DBColumnAttribute::STRING, "varchar(250)", null )]
	public const NAME_STR = "name";
	#[DBColumnAttribute( self::PRICE_FLOAT, DBColumnAttribute::FLOAT, "decimal(8, 2)", null )]
	public const PRICE_FLOAT = "price";
	#[DBColumnAttribute( self::START_TIME_STR, DBColumnAttribute::STRING, "varchar(5)", null )]
	public const START_TIME_STR = "start_time";
	#[DBColumnAttribute( self::DURATION_IN_HOURS_INT, DBColumnAttribute::INTEGER, "tinyint(3)", null )]
	public const DURATION_IN_HOURS_INT = "duration_in_hours";
	#[DBColumnAttribute( self::MONDAY_BOOL, DBColumnAttribute::INTEGER, "tinyint(1)", null )]
	public const MONDAY_BOOL = "monday";
	#[DBColumnAttribute( self::TUESDAY_BOOL, DBColumnAttribute::INTEGER, "tinyint(1)", null )]
	public const TUESDAY_BOOL = "tuesday";
	#[DBColumnAttribute( self::WEDNESDAY_BOOL, DBColumnAttribute::INTEGER, "tinyint(1)", null )]
	public const WEDNESDAY_BOOL = "wednesday";
	#[DBColumnAttribute( self::THURSDAY_BOOL, DBColumnAttribute::INTEGER, "tinyint(1)", null )]
	public const THURSDAY_BOOL = "thursday";
	#[DBColumnAttribute( self::FRIDAY_BOOL, DBColumnAttribute::INTEGER, "tinyint(1)", null )]
	public const FRIDAY_BOOL = "friday";
	#[DBColumnAttribute( self::SATURDAY_BOOL, DBColumnAttribute::INTEGER, "tinyint(1)", null )]
	public const SATURDAY_BOOL = "saturday";
	#[DBColumnAttribute( self::SUNDURDAY_BOOL, DBColumnAttribute::INTEGER, "tinyint(1)", null )]
	public const SUNDURDAY_BOOL = "sunday";
}