<?php

namespace KlintDev\WPBooking\DTO;

use AllowDynamicProperties;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;

/**
 * @template TTarget
 */
#[AllowDynamicProperties]
abstract class DTOBase {
	/**
	 * @return TTarget
	 * @throws ReflectionException
	 */
	public static function createDTO(): static {

		$class = new ReflectionClass( static::class );
		/** @var $object static */
		$object = $class->newInstanceWithoutConstructor();
		/** @var $constants array<string, string> */
		$constants = $class->getconstants();
		foreach ( $constants as $key => $value ) {
			$constant   = new ReflectionClassConstant( static::class, $key );
			$attributes = $constant->getAttributes( DTOPropertyAttribute::class );
			if ( isset( $attributes[0] ) ) {
				/** @var $attribute DTOPropertyAttribute */
				$attribute                          = $attributes[0]->newInstance();
				$object->{$attribute->PropertyName} = $attribute->DefaultValue;
			}
		}

		return $object;
	}

	/**
	 * @throws ReflectionException
	 */
	public static function assignPropertyValue( object $target, string $propertyName, mixed $propertyValue ): bool {
		$className = get_class( $target );
		$class     = new ReflectionClass( $target::class );
		/** @var $constants string[] */
		$constants = $class->getconstants();

		/** @var $attribute DTOPropertyAttribute|null */
		$attribute = null;
		foreach ( $constants as $key => $value ) {
			$reflectionConstant = new ReflectionClassConstant( $className, $key );
			$attributes         = $reflectionConstant->getAttributes( DTOPropertyAttribute::class );
			if ( ! isset( $attributes[0] ) ) {
				continue;
			}

			$foundAttribute = $attributes[0]->newInstance();
			if ( $foundAttribute->PropertyName === $propertyName ) {
				$attribute = $foundAttribute;
				break;
			}
		}

		if ( $attribute === null ) {
			throw new InvalidArgumentException( "No DTOPropertyAttribute constant found for $propertyName on $className" );
		}

		if ( $attribute->Nullable && $propertyValue === null ) {
			$target->{$propertyName} = null;

			return true;
		}

		switch ( $attribute->PropertyType ) {
			case DTOPropertyType::Bool:
			{
				if ( $propertyValue === "true" || $propertyValue === "1" ) {
					$target->{$propertyName} = true;
				} elseif ( $propertyValue === "false" || $propertyValue === "0" ) {
					$target->{$propertyName} = false;
				} elseif ( is_bool( $propertyValue ) ) {
					$target->{$propertyName} = $propertyValue;
				} else {
					throw new InvalidArgumentException( "Property value for $propertyName is not a boolean value" );
				}

				return true;
			}
			case DTOPropertyType::Int:
			{
				if ( ! is_numeric( $propertyValue ) ) {
					throw new InvalidArgumentException( "Property value for $propertyName is not an integer value" );
				}
				$target->{$propertyName} = (int) $propertyValue;

				return true;
			}
			case DTOPropertyType::Float:
			{
				if ( ! is_numeric( $propertyValue ) && ! is_float( $propertyValue ) ) {
					throw new InvalidArgumentException( "Property value for $propertyName is not a float" );
				}
				$target->{$propertyName} = $propertyValue;

				return true;
			}
			case DTOPropertyType::String:
			{
				$target->{$propertyName} = (string) $propertyValue;

				return true;
			}
			case DTOPropertyType::Array:
			{
				if ( ! is_array( $propertyValue ) ) {
					throw new InvalidArgumentException( "Property value for $propertyName is not an array" );
				}
				$target->{$propertyName} = $propertyValue;

				return true;
			}
		}
		throw new InvalidArgumentException( "Property value for $propertyName is not a valid property value" );
	}

	/**
	 * @param array<string, string|bool|int|null> $array
	 *
	 * @return TTarget
	 * @throws ReflectionException
	 */
	public static function dtoFromArray( array $array ): static {
		/** @var $object TTarget */
		$object = new static();
		foreach ( $array as $property => $value ) {
			self::assignPropertyValue( $object, $property, $value );
		}

		return $object;
	}

	public function getPropertyValue( string $propertyName ): mixed {
		if ( isset( $this->{$propertyName} ) ) {
			return $this->{$propertyName};
		}

		return null;
	}
}