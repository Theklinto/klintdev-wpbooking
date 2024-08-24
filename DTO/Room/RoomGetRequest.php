<?php

namespace KlintDev\WPBooking\DTO\Room;

use AllowDynamicProperties;
use KlintDev\WPBooking\DTO\DTOBase;
use KlintDev\WPBooking\DTO\DTOPropertyAttribute;
use KlintDev\WPBooking\DTO\DTOPropertyType;

#[AllowDynamicProperties]
class RoomGetRequest extends DTOBase
{
    #[DTOPropertyAttribute(self::ID_INT, DTOPropertyType::Int, null)]
    public const ID_INT = "Id";
    #[DTOPropertyAttribute(self::NAME_STR, DTOPropertyType::String, null)]
    public const NAME_STR = "Name";
    #[DTOPropertyAttribute(self::DESCRIPTION_STR, DTOPropertyType::String, null)]
    public const DESCRIPTION_STR = "Description";
    /**
     * Assosiative array of {@see array<int, int>[]}, where the key is priority and value is
     * the images post_id.
     */
    #[DTOPropertyAttribute(self::IMAGE_ARR, DTOPropertyType::Array, [])]
    public const IMAGE_ARR = "Images";

    public static function createDTO(): object
    {
        return self::baseCreateDTO(self::class);
    }

    public static function dtoFromArray(array $array): object
    {
        return self::baseDTOFromArray(self::class, $array);
    }
}