<?php

namespace KlintDev\WPBooking\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class DBColumnAttribute
{
    public const INTEGER = "%d";
    public const FLOAT = "%f";
    public const STRING = "%s";

    public function __construct(
        public readonly string $ColumnName,
        public readonly string $ColumnTemplateType,
        public readonly string $ColumnDefinition,
        public readonly mixed  $DefaultValue,
        public readonly bool   $Nullable = false,
        public readonly bool   $PrimaryKey = false,
    )
    {
    }
}