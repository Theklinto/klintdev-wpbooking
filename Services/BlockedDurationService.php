<?php

namespace KlintDev\WPBooking\Services;

use Exception;
use KlintDev\WPBooking\DB\DBHandler;
use KlintDev\WPBooking\DB\EntityFilter;
use KlintDev\WPBooking\DB\QueryComparisonType;
use KlintDev\WPBooking\DTO\BlockedDuration\BlockedDurationCreateRequest;
use KlintDev\WPBooking\DTO\BlockedDuration\BlockedDurationUpdateRequest;
use KlintDev\WPBooking\DTO\BlockedDuration\BlockedDurationGetRequest;
use KlintDev\WPBooking\DTO\BlockedDuration\BlockedDurationListRequest;
use KlintDev\WPBooking\DTO\DTOBase;
use KlintDev\WPBooking\Entities\BlockedDuration;
use ReflectionException;

class BlockedDurationService
{
    /**
     * @throws Exception
     */
    public static function createBlockedDuration(BlockedDurationCreateRequest $request): void
    {
        /** @var $entityArray array<string, mixed> */
        $entityArray = [];

        $entityArray[BlockedDuration::ACTIVE_BOOL] = $request->getPropertyValue(BlockedDurationCreateRequest::ACTIVE_BOOL);
        $entityArray[BlockedDuration::DESCRIPTION_STR] = $request->getPropertyValue(BlockedDurationCreateRequest::DESCRIPTION_STR);
        $entityArray[BlockedDuration::START_DATE_STR] = $request->getPropertyValue(BlockedDurationCreateRequest::START_DATE_STR);
        $entityArray[BlockedDuration::END_DATE_STR] = $request->getPropertyValue(BlockedDurationCreateRequest::END_DATE_STR);
        $entityArray[BlockedDuration::MONDAY_BOOL] = $request->getPropertyValue(BlockedDurationCreateRequest::MONDAY_BOOL);
        $entityArray[BlockedDuration::TUESDAY_BOOL] = $request->getPropertyValue(BlockedDurationCreateRequest::TUESDAY_BOOL);
        $entityArray[BlockedDuration::WEDNESDAY_BOOL] = $request->getPropertyValue(BlockedDurationCreateRequest::WEDNESDAY_BOOL);
        $entityArray[BlockedDuration::THURSDAY_BOOL] = $request->getPropertyValue(BlockedDurationCreateRequest::THURSDAY_BOOL);
        $entityArray[BlockedDuration::FRIDAY_BOOL] = $request->getPropertyValue(BlockedDurationCreateRequest::FRIDAY_BOOL);
        $entityArray[BlockedDuration::SATURDAY_BOOL] = $request->getPropertyValue(BlockedDurationCreateRequest::SATURDAY_BOOL);
        $entityArray[BlockedDuration::SUNDAY_BOOL] = $request->getPropertyValue(BlockedDurationCreateRequest::SUNDAY_BOOL);

        DBHandler::insertEntity(BlockedDuration::class, $entityArray);
    }

    /**
     * @return array<BlockedDurationListRequest>
     * @throws ReflectionException
     */
    public static function getBlockedDurations(): array
    {
        /** @var $dtos BlockedDurationListRequest[] */
        $dtos = [];
        /** @var $results BlockedDuration[] */
        $results = DBHandler::getEntitiesBy(BlockedDuration::class);
        foreach ($results as $result) {
            $dto = BlockedDurationListRequest::createDTO();
            ObjectMapper::toDTO($result, $dto, [
                BlockedDuration::ID_INT => BlockedDurationListRequest::ID_INT,
                BlockedDuration::ACTIVE_BOOL => BlockedDurationListRequest::ACTIVE_BOOL,
                BlockedDuration::DESCRIPTION_STR => BlockedDurationListRequest::DESCRIPTION_STR,
                BlockedDuration::START_DATE_STR => BlockedDurationListRequest::START_DATE_STR,
                BlockedDuration::END_DATE_STR => BlockedDurationListRequest::END_DATE_STR,
                BlockedDuration::MONDAY_BOOL => BlockedDurationListRequest::MONDAY_BOOL,
                BlockedDuration::TUESDAY_BOOL => BlockedDurationListRequest::TUESDAY_BOOL,
                BlockedDuration::WEDNESDAY_BOOL => BlockedDurationListRequest::WEDNESDAY_BOOL,
                BlockedDuration::THURSDAY_BOOL => BlockedDurationListRequest::THURSDAY_BOOL,
                BlockedDuration::FRIDAY_BOOL => BlockedDurationListRequest::FRIDAY_BOOL,
                BlockedDuration::SATURDAY_BOOL => BlockedDurationListRequest::SATURDAY_BOOL,
                BlockedDuration::SUNDAY_BOOL => BlockedDurationListRequest::SUNDAY_BOOL,
            ]);
            $dtos[] = $dto;
        }

        return $dtos;
    }

    /**
     * @param int $id
     * @return BlockedDurationGetRequest
     * @throws ReflectionException
     */
    public static function getBlockedDuration(int $id): object
    {
        $results = DBHandler::getEntitiesBy(BlockedDuration::class, [], [new EntityFilter(BlockedDuration::ID_INT, QueryComparisonType::EQUAL, $id)]);
        if (count($results) > 1) {
            throw new Exception("Multiple blocked durations found");
        }
        if (count($results) == 0) {
            throw new Exception("No blocked duration found");
        }

        /** @var BlockedDuration $result */
        $result = $results[0];

        /** @var BlockedDurationGetRequest $dto */
        $dto = BlockedDurationGetRequest::createDTO();
        ObjectMapper::toDTO($result, $dto, [
            BlockedDuration::ID_INT => BlockedDurationListRequest::ID_INT,
            BlockedDuration::ACTIVE_BOOL => BlockedDurationListRequest::ACTIVE_BOOL,
            BlockedDuration::DESCRIPTION_STR => BlockedDurationListRequest::DESCRIPTION_STR,
            BlockedDuration::START_DATE_STR => BlockedDurationListRequest::START_DATE_STR,
            BlockedDuration::END_DATE_STR => BlockedDurationListRequest::END_DATE_STR,
            BlockedDuration::MONDAY_BOOL => BlockedDurationListRequest::MONDAY_BOOL,
            BlockedDuration::TUESDAY_BOOL => BlockedDurationListRequest::TUESDAY_BOOL,
            BlockedDuration::WEDNESDAY_BOOL => BlockedDurationListRequest::WEDNESDAY_BOOL,
            BlockedDuration::THURSDAY_BOOL => BlockedDurationListRequest::THURSDAY_BOOL,
            BlockedDuration::FRIDAY_BOOL => BlockedDurationListRequest::FRIDAY_BOOL,
            BlockedDuration::SATURDAY_BOOL => BlockedDurationListRequest::SATURDAY_BOOL,
            BlockedDuration::SUNDAY_BOOL => BlockedDurationListRequest::SUNDAY_BOOL,
        ]);

        return $dto;
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public static function updateBlockedDuration(BlockedDurationUpdateRequest $request): void
    {
        $entity = ObjectMapper::toEntity($request, [], [
            BlockedDurationUpdateRequest::ID_INT => BlockedDuration::ID_INT,
            BlockedDurationUpdateRequest::ACTIVE_BOOL => BlockedDuration::ACTIVE_BOOL,
            BlockedDurationUpdateRequest::DESCRIPTION_STR => BlockedDuration::DESCRIPTION_STR,
            BlockedDurationUpdateRequest::START_DATE_STR => BlockedDuration::START_DATE_STR,
            BlockedDurationUpdateRequest::END_DATE_STR => BlockedDuration::END_DATE_STR,
            BlockedDurationUpdateRequest::MONDAY_BOOL => BlockedDuration::MONDAY_BOOL,
            BlockedDurationUpdateRequest::TUESDAY_BOOL => BlockedDuration::TUESDAY_BOOL,
            BlockedDurationUpdateRequest::WEDNESDAY_BOOL => BlockedDuration::WEDNESDAY_BOOL,
            BlockedDurationUpdateRequest::THURSDAY_BOOL => BlockedDuration::THURSDAY_BOOL,
            BlockedDurationUpdateRequest::FRIDAY_BOOL => BlockedDuration::FRIDAY_BOOL,
            BlockedDurationUpdateRequest::SATURDAY_BOOL => BlockedDuration::SATURDAY_BOOL,
            BlockedDurationUpdateRequest::SUNDAY_BOOL => BlockedDuration::SUNDAY_BOOL,
        ]);

        DBHandler::updateEntity(BlockedDuration::class, $entity);
    }

    /**
     * @throws ReflectionException
     */
    public static function deleteBlockedDurationById(int $id): void
    {
        DBHandler::DeleteEntity(BlockedDuration::class, [
            new EntityFilter(BlockedDuration::ID_INT, QueryComparisonType::EQUAL, $id)
        ]);
    }
}