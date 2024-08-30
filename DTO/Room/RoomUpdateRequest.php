<?php

namespace KlintDev\WPBooking\DTO\Room;

use AllowDynamicProperties;
use KlintDev\WPBooking\DTO\DTOBase;
use KlintDev\WPBooking\DTO\DTOPropertyAttribute;
use KlintDev\WPBooking\DTO\DTOPropertyType;
use KlintDev\WPBooking\DTO\DTOValidationTrait;
use KlintDev\WPBooking\Exceptions\ValidationFailedException;

#[AllowDynamicProperties]
class RoomUpdateRequest extends DTOBase {
	use DTOValidationTrait;

	#[DTOPropertyAttribute( self::ID_INT, DTOPropertyType::Int, null )]
	public const ID_INT = "Id";
	#[DTOPropertyAttribute( self::NAME_STR, DTOPropertyType::String, null )]
	public const NAME_STR = "Name";
	#[DTOPropertyAttribute( self::DESCRIPTION_STR, DTOPropertyType::String, null )]
	public const DESCRIPTION_STR = "Description";
	/**
	 * Assosiative array of {@see array<int, int>[]}, where the key is priority and value is
	 * the images post_id.
	 */
	#[DTOPropertyAttribute( self::IMAGE_ARR, DTOPropertyType::Array, [] )]
	public const IMAGE_ARR = "Images";

	/**
	 * @throws ValidationFailedException
	 */
	public function validate(): void {
		$this->validateInteger(
			self::ID_INT,
			"Lokale id er ikke sat!",
			1
		);

		$this->validateString(
			self::NAME_STR,
			"Navn skal være mellem 3 og 250 tegn.",
			3,
			250
		);
		$this->validateString(
			self::DESCRIPTION_STR,
			"Beskrivelse må ikke være længere end 2500 tegn.",
			0,
			2500
		);
	}
}