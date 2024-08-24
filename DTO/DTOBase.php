<?php

namespace KlintDev\WPBooking\DTO;

use AllowDynamicProperties;
use DateTime;
use ErrorException;
use Exception;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;
use WP_REST_Request;

/**
 * @template TTarget
 */
#[AllowDynamicProperties]
abstract class DTOBase
{
    abstract public static function createDTO(): object;

    /**
     * @param string $dtoClass
     * @return TTarget
     * @throws ReflectionException
     */
    protected static function baseCreateDTO(string $dtoClass): object
    {
        $class = new ReflectionClass($dtoClass);
        /** @var $object object */
        $object = $class->newInstanceWithoutConstructor();
        /** @var $constants array<string, string> */
        $constants = $class->getconstants();
        foreach ($constants as $key => $value) {
            $constant = new ReflectionClassConstant($dtoClass, $key);
            $attributes = $constant->getAttributes(DTOPropertyAttribute::class);
            if (isset($attributes[0])) {
                /** @var $attribute DTOPropertyAttribute */
                $attribute = $attributes[0]->newInstance();
                $object->{$attribute->PropertyName} = $attribute->DefaultValue;
            }
        }

        return $object;
    }

    /**
     * @throws ReflectionException
     */
    public static function assignPropertyValue(object $target, string $propertyName, mixed $propertyValue): bool
    {
        $className = get_class($target);
        $class = new ReflectionClass($target::class);
        /** @var $constants string[] */
        $constants = $class->getconstants();

        /** @var $attribute DTOPropertyAttribute|null */
        $attribute = null;
        foreach ($constants as $key => $value) {
            $reflectionConstant = new ReflectionClassConstant($className, $key);
            $attributes = $reflectionConstant->getAttributes(DTOPropertyAttribute::class);
            if (!isset($attributes[0])) {
                continue;
            }

            $foundAttribute = $attributes[0]->newInstance();
            if ($foundAttribute->PropertyName === $propertyName) {
                $attribute = $foundAttribute;
                break;
            }
        }

        if ($attribute === null) {
            throw new InvalidArgumentException("No DTOPropertyAttribute constant found for $propertyName on $className");
        }

        if ($attribute->Nullable && $propertyValue === null) {
            $target->{$propertyName} = null;
            return true;
        }

        switch ($attribute->PropertyType) {
            case DTOPropertyType::Bool:
            {
                if ($propertyValue === "true" || $propertyValue === "1") {
                    $target->{$propertyName} = true;
                } elseif ($propertyValue === "false" || $propertyValue === "0") {
                    $target->{$propertyName} = false;
                } elseif (is_bool($propertyValue)) {
                    $target->{$propertyName} = $propertyValue;
                } else {
                    throw new InvalidArgumentException("Property value $propertyValue is not a boolean value");
                }
                return true;
            }
            case DTOPropertyType::Int:
            {
                if (!is_numeric($propertyValue)) {
                    throw new InvalidArgumentException("Property value $propertyValue is not an integer value");
                }
                $target->{$propertyName} = (int)$propertyValue;
                return true;
            }
            case DTOPropertyType::Float:
            {
                if (!is_float($propertyValue)) {
                    throw new InvalidArgumentException("Property value $propertyValue is not a float");
                }
                $target->{$propertyName} = $propertyValue;
                return true;
            }
            case DTOPropertyType::String:
            {
                $target->{$propertyName} = (string)$propertyValue;
                return true;
            }
            case DTOPropertyType::Array:
            {
                if (!is_array($propertyValue)) {
                    throw new InvalidArgumentException("Property value $propertyValue is not an array");
                }
                $target->{$propertyName} = $propertyValue;
                return true;
            }
        }
        throw new InvalidArgumentException("Property value of type $propertyValue is not a valid property value");
    }

    /**
     * @param array<string, string|bool|int|null> $array
     * @return object
     */
    abstract public static function dtoFromArray(array $array): object;

    /**
     * @param string $targetClass
     * @param array<string, string|bool|int|null> $array
     * @return TTarget
     * @throws ReflectionException
     */
    protected static function baseDTOFromArray(string $targetClass, array $array): object
    {
        /** @var $object TTarget */
        $object = new $targetClass();
        foreach ($array as $property => $value) {
            self::assignPropertyValue($object, $property, $value);
        }

        return $object;
    }

    public function getPropertyValue(string $propertyName): mixed
    {
        if (isset($this->{$propertyName})) {
            return $this->{$propertyName};
        }

        return null;
    }

    /**
     * @throws Exception
     */
    protected static function validateDate(DTOBase $target, string $propertyName, string $errorMessage): void
    {
        $value = $target->getPropertyValue($propertyName);
        try {
            if (strlen($value) == 0) {
                throw new Exception($errorMessage);
            }

            new DateTime($value);
        } catch (Exception $e) {
            throw new Exception($errorMessage);
        }
    }

    /**
     * @throws Exception
     */
    protected static function validateString(
        DTOBase $target,
        string  $propertyName,
        string  $errorMessage,
        int     $minLength,
        int     $maxLength): void
    {
        $value = $target->getPropertyValue($propertyName);
        if (strlen($value) < $minLength || strlen($value) > $maxLength) {
            throw new Exception($errorMessage);
        }
    }
}