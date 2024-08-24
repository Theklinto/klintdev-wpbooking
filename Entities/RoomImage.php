<?php

namespace KlintDev\WPBooking\Entities;

use AllowDynamicProperties;
use KlintDev\WPBooking\Attributes\DBColumnAttribute;
use KlintDev\WPBooking\Attributes\DBTableAttribute;

#[AllowDynamicProperties]
#[DBTableAttribute("room_image", 1)]
class RoomImage
{
    #[DBColumnAttribute(self::ID_INT, DBColumnAttribute::INTEGER, "bigint unsigned", null, false, true)]
    public const ID_INT = "id";
    #[DBColumnAttribute(self::ROOM_ID_INT, DBColumnAttribute::INTEGER, "bigint unsigned", null)]
    public const ROOM_ID_INT = "room_id";
    #[DBColumnAttribute(self::POST_ID_INT, DBColumnAttribute::INTEGER, "bigint unsigned", null)]
    public const POST_ID_INT = "post_id";
    #[DBColumnAttribute(self::PRIORITY_INT, DBColumnAttribute::INTEGER, "tinyint(2) unsigned", null)]
    public const PRIORITY_INT = "priority";
}