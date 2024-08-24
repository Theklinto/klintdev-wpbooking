<?php

namespace KlintDev\WPBooking\Attributes;

use Attribute;
use KlintDev\WPBooking\GlobalSettings;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class DBTableAttribute
{
    public function __construct(
        public string $TableName,
        public int    $TableVersion,
    )
    {
    }

    public function getPrefixedTableName(): string
    {
        global $wpdb;
        return $wpdb->prefix . GlobalSettings::PLUGIN_PREFIX . "_" . $this->TableName;
    }
}