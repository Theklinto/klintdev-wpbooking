<?php

namespace KlintDev\WPBooking\DTO\StripeSettings;

use KlintDev\WPBooking\DTO\DTOBase;
use KlintDev\WPBooking\DTO\DTOPropertyAttribute;
use KlintDev\WPBooking\DTO\DTOPropertyType;
use ReflectionException;

/**
 * @implements GetStripeSettingsRequest
 */
class GetStripeSettingsRequest extends DTOBase
{

    #[DTOPropertyAttribute(self::API_KEY, DTOPropertyType::String, "")]
    public const API_KEY = "ApiKey";
    #[DTOPropertyAttribute(self::API_SECRET, DTOPropertyType::String, "")]
    public const API_SECRET = "ApiSecret";
}