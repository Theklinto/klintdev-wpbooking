<?php

namespace KlintDev\WPBooking\Services;

use KlintDev\WPBooking\DB\DBHandler;
use KlintDev\WPBooking\DB\EntityFilter;
use KlintDev\WPBooking\DB\QueryComparisonType;
use KlintDev\WPBooking\DTO\Package\PackageCreateRequest;
use KlintDev\WPBooking\DTO\Package\PackageGetRequest;
use KlintDev\WPBooking\DTO\Package\PackageListRequest;
use KlintDev\WPBooking\DTO\Package\PackageUpdateRequest;
use KlintDev\WPBooking\Entities\Package;
use ReflectionException;

class PackageService {
	/**
	 * @throws ReflectionException
	 */
	public static function createPackage( PackageCreateRequest $request ): void {
		$entity = new Package();
		ObjectMapper::toEntity( $request, $entity, [
			PackageCreateRequest::ACTIVE_BOOL           => Package::ACTIVE_BOOL,
			PackageCreateRequest::NAME_STR              => Package::NAME_STR,
			PackageCreateRequest::ROOM_ID_INT           => Package::ROOM_ID_INT,
			PackageCreateRequest::PRICE_FLOAT           => Package::PRICE_FLOAT,
			PackageCreateRequest::DEPOSIT_FLOAT         => Package::DEPOSIT_FLOAT,
			PackageCreateRequest::START_TIME_STR        => Package::START_TIME_STR,
			PackageCreateRequest::DURATION_IN_HOURS_INT => Package::DURATION_IN_HOURS_INT,
			PackageCreateRequest::MONDAY_BOOL           => Package::MONDAY_BOOL,
			PackageCreateRequest::TUESDAY_BOOL          => Package::TUESDAY_BOOL,
			PackageCreateRequest::WEDNESDAY_BOOL        => Package::WEDNESDAY_BOOL,
			PackageCreateRequest::THURSDAY_BOOL         => Package::THURSDAY_BOOL,
			PackageCreateRequest::FRIDAY_BOOL           => Package::FRIDAY_BOOL,
			PackageCreateRequest::SATURDAY_BOOL         => Package::SATURDAY_BOOL,
			PackageCreateRequest::SUNDURDAY_BOOL        => Package::SUNDURDAY_BOOL,
		] );

		DBHandler::insertEntity( Package::class, $entity );
	}

	/**
	 * @throws ReflectionException
	 */
	public static function updatePackage( PackageUpdateRequest $request ): void {
		$entity = new Package();
		ObjectMapper::toEntity( $request, $entity, [
			PackageUpdateRequest::ID_INT                => Package::ID_INT,
			PackageUpdateRequest::ACTIVE_BOOL           => Package::ACTIVE_BOOL,
			PackageUpdateRequest::NAME_STR              => Package::NAME_STR,
			PackageUpdateRequest::ROOM_ID_INT           => Package::ROOM_ID_INT,
			PackageUpdateRequest::PRICE_FLOAT           => Package::PRICE_FLOAT,
			PackageUpdateRequest::DEPOSIT_FLOAT         => Package::DEPOSIT_FLOAT,
			PackageUpdateRequest::START_TIME_STR        => Package::START_TIME_STR,
			PackageUpdateRequest::DURATION_IN_HOURS_INT => Package::DURATION_IN_HOURS_INT,
			PackageUpdateRequest::MONDAY_BOOL           => Package::MONDAY_BOOL,
			PackageUpdateRequest::TUESDAY_BOOL          => Package::TUESDAY_BOOL,
			PackageUpdateRequest::WEDNESDAY_BOOL        => Package::WEDNESDAY_BOOL,
			PackageUpdateRequest::THURSDAY_BOOL         => Package::THURSDAY_BOOL,
			PackageUpdateRequest::FRIDAY_BOOL           => Package::FRIDAY_BOOL,
			PackageUpdateRequest::SATURDAY_BOOL         => Package::SATURDAY_BOOL,
			PackageUpdateRequest::SUNDURDAY_BOOL        => Package::SUNDURDAY_BOOL,
		] );

		DBHandler::updateEntity( Package::class, $entity );
	}

	/**
	 * @throws ReflectionException
	 */
	public static function deletePackage( int $id ): void {
		DBHandler::deleteEntity( Package::class, [
			new EntityFilter(
				Package::ID_INT,
				QueryComparisonType::EQUAL,
				$id
			)
		] );
	}

	/**
	 * @return PackageListRequest[]
	 * @throws ReflectionException
	 */
	public static function getPackagesList(): array {
		return DBHandler::getPackagesWithRoomName();
	}

	/**
	 * @throws ReflectionException
	 */
	public static function getPackageById( int $id ): PackageGetRequest|null {
		$results = DBHandler::getEntitiesBy( Package::class, [], [
			new EntityFilter(
				Package::ID_INT,
				QueryComparisonType::EQUAL,
				$id
			)
		] );

		if ( ! isset( $results[0] ) ) {
			return null;
		}
		$package = $results[0];

		$dto = PackageGetRequest::createDTO();
		ObjectMapper::toDTO( $package, $dto, [
			Package::ID_INT                => PackageGetRequest::ID_INT,
			Package::ACTIVE_BOOL           => PackageGetRequest::ACTIVE_BOOL,
			Package::NAME_STR              => PackageGetRequest::NAME_STR,
			Package::ROOM_ID_INT           => PackageGetRequest::ROOM_ID_INT,
			Package::PRICE_FLOAT           => PackageGetRequest::PRICE_FLOAT,
			Package::DEPOSIT_FLOAT         => PackageGetRequest::DEPOSIT_FLOAT,
			Package::START_TIME_STR        => PackageGetRequest::START_TIME_STR,
			Package::DURATION_IN_HOURS_INT => PackageGetRequest::DURATION_IN_HOURS_INT,
			Package::MONDAY_BOOL           => PackageGetRequest::MONDAY_BOOL,
			Package::TUESDAY_BOOL          => PackageGetRequest::TUESDAY_BOOL,
			Package::WEDNESDAY_BOOL        => PackageGetRequest::WEDNESDAY_BOOL,
			Package::THURSDAY_BOOL         => PackageGetRequest::THURSDAY_BOOL,
			Package::FRIDAY_BOOL           => PackageGetRequest::FRIDAY_BOOL,
			Package::SATURDAY_BOOL         => PackageGetRequest::SATURDAY_BOOL,
			Package::SUNDURDAY_BOOL        => PackageGetRequest::SUNDURDAY_BOOL,
		] );

		return $dto;
	}
}