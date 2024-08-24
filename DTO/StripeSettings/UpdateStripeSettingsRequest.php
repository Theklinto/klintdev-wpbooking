<?php

namespace KlintDev\WPBooking\DTO\StripeSettings;

use Attribute;
use KlintDev\WPBooking\DTO\DTOBase;
use KlintDev\WPBooking\DTO\DTOPropertyAttribute;
use KlintDev\WPBooking\DTO\DTOPropertyType;
use ReflectionException;

/**
 * @implements UpdateStripeSettingsRequest
 */
class UpdateStripeSettingsRequest extends DTOBase
{
    #[DTOPropertyAttribute(self::API_KEY, DTOPropertyType::String, "")]
    public const API_KEY = "ApiKey";

    #[DTOPropertyAttribute(self::API_SECRET, DTOPropertyType::String, "")]
    public const API_SECRET = "ApiSecret";

    /**
     * @throws ReflectionException
     * @return UpdateStripeSettingsRequest
     */
    public static function createDTO(): object
    {
        return self::baseCreateDTO(UpdateStripeSettingsRequest::class);
    }

    /**
     * @param array $array
     * @return UpdateStripeSettingsRequest
     */
    public static function dtoFromArray(array $array): object
    {
        return self::baseDTOFromArray(UpdateStripeSettingsRequest::class, $array);
    }
}

