<?php

namespace KlintDev\WPBooking\Entities;

use AllowDynamicProperties;
use KlintDev\WPBooking\Attributes\DBColumnAttribute;
use KlintDev\WPBooking\Attributes\DBTableAttribute;

#[AllowDynamicProperties]
#[DBTableAttribute("room", 1)]
class Room
{
    #[DBColumnAttribute(self::ID_INT, DBColumnAttribute::INTEGER, "bigint unsigned", null, false, true)]
    public const ID_INT = "id";
    #[DBColumnAttribute(self::NAME_STR, DBColumnAttribute::STRING, "varchar(250)", null)]
    public const NAME_STR = "name";
    #[DBColumnAttribute(self::DESCRIPTION_STR, DBColumnAttribute::STRING, "varchar(2500)", null)]
    public const DESCRIPTION_STR = "description";
}