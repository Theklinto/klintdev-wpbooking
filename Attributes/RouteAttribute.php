<?php
namespace KlintDev\WPBooking\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class RouteAttribute
{
    public function __construct(
        public readonly string $Route,
        public readonly string|null $Method = null,
        public readonly bool $RequiredAdmin = true
    ) {
    }
}