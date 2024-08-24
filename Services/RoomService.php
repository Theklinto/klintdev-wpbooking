<?php

namespace KlintDev\WPBooking\Services;

use Exception;
use KlintDev\WPBooking\DB\DBHandler;
use KlintDev\WPBooking\DB\EntityFilter;
use KlintDev\WPBooking\DB\QueryComparisonType;
use KlintDev\WPBooking\DTO\DTOBase;
use KlintDev\WPBooking\DTO\Room\RoomCreateRequest;
use KlintDev\WPBooking\DTO\Room\RoomGetRequest;
use KlintDev\WPBooking\DTO\Room\RoomListRequest;
use KlintDev\WPBooking\DTO\Room\RoomUpdateRequest;
use KlintDev\WPBooking\Entities\Room;
use KlintDev\WPBooking\Entities\RoomImage;
use ReflectionException;

class RoomService
{
    /**
     * @throws Exception
     */
    public static function createRoom(RoomCreateRequest $dto): void
    {
        try {

            $room = new Room();
            ObjectMapper::toEntity($dto, $room, [
                RoomCreateRequest::NAME_STR => Room::NAME_STR,
                RoomCreateRequest::DESCRIPTION_STR => Room::DESCRIPTION_STR,
            ]);

            $roomId = DBHandler::InsertEntity(Room::class, $room);

            /** @var array<int, int> $dtoImages */
            $dtoImages = $dto->getPropertyValue(RoomCreateRequest::IMAGE_ARR);
            if (!empty($dtoImages)) {
                foreach ($dtoImages as $priority => $postId) {
                    $image = [
                        RoomImage::PRIORITY_INT => $priority,
                        RoomImage::POST_ID_INT => $postId,
                        RoomImage::ROOM_ID_INT => $roomId,
                    ];
                    DBHandler::InsertEntity(RoomImage::class, $image);
                }
            }

            DBHandler::commitTransaction();
        } catch (Exception $e) {
            DBHandler::rollbackTransaction();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public static function updateRoom(RoomUpdateRequest $dto): void
    {
        try {
            DBHandler::startTransaction();

            DBHandler::deleteEntity(RoomImage::class, [
                new EntityFilter(
                    RoomImage::ROOM_ID_INT,
                    QueryComparisonType::EQUAL,
                    $dto->getPropertyValue(RoomUpdateRequest::ID_INT)),
            ]);

            $roomEntity = new Room();
            ObjectMapper::toEntity($dto, $roomEntity, [
                RoomUpdateRequest::NAME_STR => Room::NAME_STR,
                RoomUpdateRequest::DESCRIPTION_STR => Room::DESCRIPTION_STR,
                RoomUpdateRequest::ID_INT => Room::ID_INT,
            ]);
            DBHandler::updateEntity(Room::class, $roomEntity);

            foreach ($dto->getPropertyValue(RoomUpdateRequest::IMAGE_ARR) as $priority => $postId) {
                $image = new RoomImage();
                $image->{RoomImage::PRIORITY_INT} = $priority;
                $image->{RoomImage::POST_ID_INT} = $postId;
                $image->{RoomImage::ROOM_ID_INT} = $dto->getPropertyValue(RoomUpdateRequest::ID_INT);
                DBHandler::insertEntity(RoomImage::class, $image);
            }

            DBHandler::commitTransaction();

        } catch (Exception $e) {
            DBHandler::rollbackTransaction();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public static function deleteRoom(int $roomId): void
    {
        try {
            DBHandler::startTransaction();

            DBHandler::deleteEntity(RoomImage::class, [
                new EntityFilter(
                    RoomImage::ROOM_ID_INT,
                    QueryComparisonType::EQUAL,
                    $roomId,
                )
            ]);
            DBHandler::deleteEntity(Room::class, [
                new EntityFilter(
                    Room::ID_INT,
                    QueryComparisonType::EQUAL,
                    $roomId,
                )
            ]);

            DBHandler::commitTransaction();
        } catch (Exception $e) {
            DBHandler::rollbackTransaction();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @return RoomListRequest[]
     * @throws ReflectionException
     */
    public static function getRooms(): array
    {
        return DBHandler::getRoomsWithImage();
    }

    /**
     * @throws ReflectionException
     */
    public static function getRoomById(int $id): RoomGetRequest
    {
        $room = DBHandler::getEntitiesBy(Room::class, [], [
            new EntityFilter(Room::ID_INT, QueryComparisonType::EQUAL, $id)
        ])[0];
        $images = DBHandler::getEntitiesBy(RoomImage::class, [
            RoomImage::PRIORITY_INT,
            RoomImage::POST_ID_INT
        ], [
            new EntityFilter(RoomImage::ROOM_ID_INT, QueryComparisonType::EQUAL, $id)
        ]);
        $dto = RoomGetRequest::createDTO();
        ObjectMapper::toDTO($room, $dto, [
            Room::ID_INT => RoomGetRequest::ID_INT,
            Room::NAME_STR => RoomGetRequest::NAME_STR,
            Room::DESCRIPTION_STR => RoomGetRequest::DESCRIPTION_STR,
        ]);
        $imageArr = [];
        foreach ($images as $image) {
            $imageArr[$image->{RoomImage::PRIORITY_INT}] = $image->{RoomImage::POST_ID_INT};
        }

        ksort($imageArr);
        DTOBase::assignPropertyValue($dto, RoomGetRequest::IMAGE_ARR, $imageArr);

        return $dto;
    }
}