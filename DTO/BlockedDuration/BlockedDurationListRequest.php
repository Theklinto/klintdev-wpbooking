<?php

namespace KlintDev\WPBooking\DTO\BlockedDuration;

use KlintDev\WPBooking\DTO\DTOBase;
use KlintDev\WPBooking\DTO\DTOPropertyAttribute;
use KlintDev\WPBooking\DTO\DTOPropertyType;

class BlockedDurationListRequest extends DTOBase
{
    #[DTOPropertyAttribute(self::ID_INT, DTOPropertyType::Int, 0)]
    public const ID_INT = "Id";
    #[DTOPropertyAttribute(self::ACTIVE_BOOL, DTOPropertyType::Bool, false)]
    public const ACTIVE_BOOL = "Active";
    #[DTOPropertyAttribute(self::START_DATE_STR, DTOPropertyType::String, "")]
    public const START_DATE_STR = "StartDate";
    #[DTOPropertyAttribute(self::END_DATE_STR, DTOPropertyType::String, "")]
    public const END_DATE_STR = "EndDate";
    #[DTOPropertyAttribute(self::DESCRIPTION_STR, DTOPropertyType::String, "")]
    public const DESCRIPTION_STR = "Description";
    #[DTOPropertyAttribute(self::MONDAY_BOOL, DTOPropertyType::Bool, false)]
    public const MONDAY_BOOL = "Monday";
    #[DTOPropertyAttribute(self::TUESDAY_BOOL, DTOPropertyType::Bool, false)]
    public const TUESDAY_BOOL = "Tuesday";
    #[DTOPropertyAttribute(self::WEDNESDAY_BOOL, DTOPropertyType::Bool, false)]
    public const WEDNESDAY_BOOL = "Wednesday";
    #[DTOPropertyAttribute(self::THURSDAY_BOOL, DTOPropertyType::Bool, false)]
    public const THURSDAY_BOOL = "Thursday";
    #[DTOPropertyAttribute(self::FRIDAY_BOOL, DTOPropertyType::Bool, false)]
    public const FRIDAY_BOOL = "Friday";
    #[DTOPropertyAttribute(self::SATURDAY_BOOL, DTOPropertyType::Bool, false)]
    public const SATURDAY_BOOL = "Saturday";
    #[DTOPropertyAttribute(self::SUNDAY_BOOL, DTOPropertyType::Bool, false)]
    public const SUNDAY_BOOL = "Sunday";
}