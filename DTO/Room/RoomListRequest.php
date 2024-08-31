<?php

namespace KlintDev\WPBooking\DTO\Room;

use KlintDev\WPBooking\DTO\DTOBase;
use KlintDev\WPBooking\DTO\DTOPropertyAttribute;
use KlintDev\WPBooking\DTO\DTOPropertyType;

class RoomListRequest extends DTOBase
{
    #[DTOPropertyAttribute(self::ID_INT, DTOPropertyType::Int, null)]
    public const ID_INT = "Id";
    #[DTOPropertyAttribute(self::NAME_STR, DTOPropertyType::String, null)]
    public const NAME_STR = "Name";
    #[DTOPropertyAttribute(self::DESCRIPTION_STR, DTOPropertyType::String, null)]
    public const DESCRIPTION_STR = "Description";
    #[DTOPropertyAttribute(self::IMAGE_POST_ID_INT, DTOPropertyType::Int, null)]
    public const IMAGE_POST_ID_INT = "ImagePostId";
}