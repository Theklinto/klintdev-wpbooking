<?php

namespace KlintDev\WPBooking\DTO;

use DateTime;
use Exception;
use KlintDev\WPBooking\Exceptions\ValidationFailedException;
use KlintDev\WPBooking\Utilities\Regex;

/**
 * Set of common validation functions. Use this trait on DTO's that requires validation.
 */
trait DTOValidationTrait {
	/**
	 * Validate if the DTO is valid. Thorws an {@see ValidationFailedException} if validation fails.
	 * Other exceptions are not from validation requirements.
	 * @return void
	 */
	abstract public function validate(): void;

	/**
	 * Validates if a string can be converted to a valid {@see DateTime}
	 * @throws ValidationFailedException
	 * @throws Exception
	 */
	protected function validateDate(string $propertyName, string $errorMessage ): void {
		$value = $this->getPropertyValue( $propertyName );
		try {
			if ( strlen( $value ) == 0 ) {
				throw new ValidationFailedException( $errorMessage );
			}

			new DateTime( $value );
		} catch ( Exception $e ) {
			throw new Exception( $errorMessage );
		}
	}

	/**
	 * Validates if a {@see string} is within the required length.
	 * @throws Exception
	 * @throws ValidationFailedException
	 */
	protected function validateString(
		string $propertyName,
		string $errorMessage,
		int $minLength,
		int $maxLength
	): void {
		$value = $this->getPropertyValue( $propertyName );
		if ( strlen( $value ) < $minLength || strlen( $value ) > $maxLength ) {
			throw new ValidationFailedException( $errorMessage );
		}
	}

	/**
	 * Validates if the {@see int} is within the allowed range.
	 * @throws Exception
	 * @throws ValidationFailedException
	 */
	protected function validateInteger(
		string $propertyName,
		string $errorMessage,
		?int $minValue = null,
		?int $maxValue = null
	): void {
		$value = $this->getPropertyValue( $propertyName );

		if ( ( isset( $minValue ) && $value < $minValue ) || ( isset( $maxValue ) && $value > $maxValue ) ) {
			throw new ValidationFailedException( $errorMessage );
		}
	}

	/**
	 * Validates if a string matches the provided regex. Is valid if the regex is matched
	 * 1 or more times. See {@see Regex} for predefined regex expressions.
	 * @throws Exception
	 * @throws ValidationFailedException
	 */
	protected function validateStringRegex(
		string $propertyName,
		string $errorMessage,
		string $regex,
	): void {
		$value = $this->getPropertyValue( $propertyName );
		if ( preg_match( $regex, $value ) < 1 ) {
			throw new ValidationFailedException( $errorMessage );
		}
	}
}