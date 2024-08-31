<?php
namespace KlintDev\WPBooking\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
readonly class RouteAttribute
{
    public function __construct(
        public string $Route,
        public string|null $Method = null,
        public bool $RequiredAdmin = true
    ) {
    }
}