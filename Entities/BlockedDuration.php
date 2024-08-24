<?php

namespace KlintDev\WPBooking\Entities;

use AllowDynamicProperties;
use KlintDev\WPBooking\Attributes\DBColumnAttribute;
use KlintDev\WPBooking\Attributes\DBTableAttribute;

#[AllowDynamicProperties]
#[DBTableAttribute("blocked_duration", 1)]
class BlockedDuration
{
    #[DBColumnAttribute(self::ID_INT, DBColumnAttribute::INTEGER, "bigint unsigned", null, false, true)]
    public const ID_INT = "id";
    #[DBColumnAttribute(self::ACTIVE_BOOL, DBColumnAttribute::INTEGER, "tinyint(1)", 0)]
    public const ACTIVE_BOOL = "active";
    #[DBColumnAttribute(self::START_DATE_STR, DBColumnAttribute::STRING, "date", null)]
    public const START_DATE_STR = "start_date";
    #[DBColumnAttribute(self::END_DATE_STR, DBColumnAttribute::STRING, "date", null, true)]
    public const END_DATE_STR = "end_date";
    #[DBColumnAttribute(self::DESCRIPTION_STR, DBColumnAttribute::STRING, "varchar(1000)", null)]
    public const DESCRIPTION_STR = "description";
    #[DBColumnAttribute(self::MONDAY_BOOL, DBColumnAttribute::INTEGER, "tinyint(1)", 0)]
    public const MONDAY_BOOL = "monday";
    #[DBColumnAttribute(self::TUESDAY_BOOL, DBColumnAttribute::INTEGER, "tinyint(1)", 0)]
    public const TUESDAY_BOOL = "tuesday";
    #[DBColumnAttribute(self::WEDNESDAY_BOOL, DBColumnAttribute::INTEGER, "tinyint(1)", 0)]
    public const WEDNESDAY_BOOL = "wednesday";
    #[DBColumnAttribute(self::THURSDAY_BOOL, DBColumnAttribute::INTEGER, "tinyint(1)", 0)]
    public const THURSDAY_BOOL = "thursday";
    #[DBColumnAttribute(self::FRIDAY_BOOL, DBColumnAttribute::INTEGER, "tinyint(1)", 0)]
    public const FRIDAY_BOOL = "friday";
    #[DBColumnAttribute(self::SATURDAY_BOOL, DBColumnAttribute::INTEGER, "tinyint(1)", 0)]
    public const SATURDAY_BOOL = "saturday";
    #[DBColumnAttribute(self::SUNDAY_BOOL, DBColumnAttribute::INTEGER, "tinyint(1)", 0)]
    public const SUNDAY_BOOL = "sunday";
}