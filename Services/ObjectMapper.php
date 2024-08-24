<?php

namespace KlintDev\WPBooking\Services;

use KlintDev\WPBooking\DTO\DTOBase;
use ReflectionException;

class ObjectMapper
{
    /**
     * @param object $source
     * @param object $target
     * @param array<string, string> $map
     * @return void
     * @throws ReflectionException
     */
    public static function toDTO(object $source, object $target, array $map): void
    {
        foreach ($map as $sourceProperty => $targetProperty) {
            if (isset($source->{$sourceProperty})) {
                DTOBase::assignPropertyValue($target, $targetProperty, $source->{$sourceProperty});
            }
        }
    }

    /**
     * @param DTOBase $source
     * @param object|array $target
     * @param array $map
     * @return object|array
     */
    public static function toEntity(DTOBase $source, object|array $target, array $map): object|array
    {
        foreach ($map as $sourceProperty => $targetProperty) {
            if (gettype($target) == 'array') {
                $target[$targetProperty] = $source->getPropertyValue($sourceProperty);
            } else {
                $target->{$targetProperty} = $source->getPropertyValue($sourceProperty);
            }
        }

        return $target;
    }
}