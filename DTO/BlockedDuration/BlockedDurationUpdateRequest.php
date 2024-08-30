<?php

namespace KlintDev\WPBooking\DTO\BlockedDuration;

use Exception;
use KlintDev\WPBooking\DTO\DTOBase;
use KlintDev\WPBooking\DTO\DTOPropertyAttribute;
use KlintDev\WPBooking\DTO\DTOPropertyType;
use KlintDev\WPBooking\DTO\DTOValidationTrait;

class BlockedDurationUpdateRequest extends DTOBase {
	use DTOValidationTrait;

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

    /**
     * @throws Exception
     */
    public function validate(): void
    {
	    $this->validateDate(
            self::START_DATE_STR,
            "Start dato skal være angivet!");
	    $this->validateString(
            self::DESCRIPTION_STR,
            "Beskrivelsen skal være mellem 3 og 1000 tegn",
            3,
            1000
        );
    }
}