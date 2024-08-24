<?php

namespace KlintDev\WPBooking\DB;

use Exception;
use KlintDev\WPBooking\Attributes\DBColumnAttribute;
use KlintDev\WPBooking\Attributes\DBTableAttribute;
use KlintDev\WPBooking\DTO\Room\RoomListRequest;
use KlintDev\WPBooking\Entities\BlockedDuration;
use KlintDev\WPBooking\Entities\Room;
use KlintDev\WPBooking\Entities\RoomImage;
use KlintDev\WPBooking\Services\ObjectMapper;
use ReflectionClass;
use ReflectionException;

class DBHandler
{
    //<editor-fold desc="Create tables">
    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public static function createTables(): void
    {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $entities = [
            BlockedDuration::class,
            Room::class,
            RoomImage::class,
        ];

        foreach ($entities as $entity) {
            $table = self::getTableDefinition($entity);
            $createTableQuery = self::getCreateTableString($entity);

            $result = dbDelta($createTableQuery);

            $tableName = $table->getPrefixedTableName();
            $tableResult = $wpdb->get_var("SHOW TABLES LIKE '$tableName'");
            if ($tableResult != $tableName) {
                throw new Exception("Table '$tableName' was not created!\n" . join("\n ", $result));
            }

            update_option("db_version_$tableName", $createTableQuery);
        }
    }

    /**
     * @throws ReflectionException
     */
    protected static function getCreateTableString(string $className): string
    {
        global $wpdb;
        $createTableString = "CREATE TABLE %s ( %s, %s ) collate {$wpdb->collate}";

        /** @var $dbTable DBTableAttribute */
        $dbTable = self::getTableDefinition($className);
        $tableName = $dbTable->getPrefixedTableName();

        /** @var $columnStrings string[] */
        $columnStrings = [];
        $primaryKeyDefinition = "";

        /** @var $properties array<string, DBColumnAttribute> */
        $properties = self::getColumnDefinitions($className);

        foreach ($properties as $entityString => $columnAttribute) {

            $columnDefinition = [
                $columnAttribute->ColumnName,
                $columnAttribute->ColumnDefinition,
                $columnAttribute->Nullable ? "NULL" : "NOT NULL"
            ];
            if (isset($columnAttribute->DefaultValue)) {
                $columnDefinition[] = "DEFAULT '{$columnAttribute->DefaultValue}'";
            }
            if ($columnAttribute->PrimaryKey) {
                $columnDefinition[] = "AUTO_INCREMENT";
                $primaryKeyDefinition = "PRIMARY KEY  ({$columnAttribute->ColumnName})";
            }

            $columnStrings[] = join(" ", $columnDefinition);
        }

        $columnsString = join(", ", $columnStrings);
        return sprintf($createTableString, $tableName, $columnsString, $primaryKeyDefinition);
    }
    //</editor-fold>

    /**
     * @param string $entityClass
     * @param string[] $includeColumns
     * @param EntityFilter[] $filterBy
     * @return array
     * @throws ReflectionException
     */
    public static function getEntitiesBy(
        string $entityClass,
        array  $includeColumns = [],
        array  $filterBy = [],
    ): array
    {
        global $wpdb;

        $columnSelector = self::getColumnSelector($entityClass, $includeColumns);

        $tableDefinition = self::getTableDefinition($entityClass);
        $tableName = $tableDefinition->getPrefixedTableName();

        self::createFilters($entityClass, $filterBy);
        $filtersWithPlaceholder = [];
        $arguments = [];
        foreach ($filterBy as $filter) {
            $filtersWithPlaceholder[] = $filter->getQueryPlaceholderString();
            $arguments[] = $filter->value;
        }

        $filtersWithPlaceholderString = join(", ", $filtersWithPlaceholder);

        $query = "";
        if (count($arguments) > 0) {
            $selectString = sprintf(
                "SELECT %s FROM %s WHERE %s",
                $columnSelector,
                $tableName,
                $filtersWithPlaceholderString
            );
            $query = $wpdb->prepare(
                $selectString,
                $arguments
            );
        } else {
            $query = sprintf(
                "SELECT %s FROM %s",
                $columnSelector,
                $tableName
            );
        }


        $rows = $wpdb->get_results($query, ARRAY_A);

        /** @var $results object[] */
        $results = [];
        foreach ($rows as $index => $row) {
            $results[] = self::mapRowToObject($entityClass, $row);
        }

        return $results;
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public static function updateEntity(string $entityClass, array|object $entity): bool|string
    {
        global $wpdb;

        $entity = (array)$entity;

        /** @var $columns array<string, DBColumnAttribute> } */
        $columns = self::getColumnDefinitions($entityClass);

        /** @var $dataArray array<string, mixed> */
        $dataArray = [];
        /** @var $dataTypes array<string> */
        $dataTypes = [];
        /** @var $whereArray array<string, mixed> */
        $whereArray = [];
        /** @var $whereTypes array<string> */
        $whereTypes = [];
        foreach ($columns as $entityConstant => $columnDefinition) {
            if (!isset($entity[$entityConstant])) {
                continue;
            }

            if ($columnDefinition->PrimaryKey) {
                $whereArray[$columnDefinition->ColumnName] = $entity[$entityConstant];
                $whereTypes[] = $columnDefinition->ColumnTemplateType;
                continue;
            }

            $dataArray[$columnDefinition->ColumnName] = $entity[$entityConstant];
            $dataTypes[] = $columnDefinition->ColumnTemplateType;
        }

        if (count($whereArray) == 0) {
            throw new  Exception("No where primary key was specified");
        }
        if (count($dataArray) == 0) {
            throw  new Exception("No data was specified");
        }

        $tableDefinition = self::getTableDefinition($entityClass);

        $result = $wpdb->update(
            $tableDefinition->getPrefixedTableName(),
            $dataArray,
            $whereArray,
            $dataTypes,
            $whereTypes
        );

        if ($result === false) {
            throw new Exception("An error occured during update $wpdb->error");
        }

        return true;
    }

    /**
     * @param string $entityClass
     * @param array|object $entity
     * @return int Inserted id
     * @throws ReflectionException
     */
    public static function insertEntity(string $entityClass, array|object $entity): int
    {
        global $wpdb;

        $entity = (array)$entity;

        /** @var $columns array<string, DBColumnAttribute> } */
        $columns = self::getColumnDefinitions($entityClass);

        /** @var $dataArray array<string, mixed> */
        $dataArray = [];
        /** @var $dataTypes array<string> */
        $whereTypes = [];

        foreach ($columns as $entityConstant => $columnDefinition) {
            if (!isset($entity[$entityConstant])) {
                continue;
            }

            $dataArray[$columnDefinition->ColumnName] = $entity[$entityConstant];
            $dataTypes[] = $columnDefinition->ColumnTemplateType;
        }

        if (count($dataArray) == 0) {
            throw new Exception("No data was specified");
        }

        $tableDefinition = self::getTableDefinition($entityClass);

        $result = $wpdb->insert(
            $tableDefinition->getPrefixedTableName(),
            $dataArray,
            $dataTypes,
        );

        if ($result !== 1) {
            throw new Exception("An error occured during insert $wpdb->error");
        }

        return $wpdb->insert_id;
    }

    /**
     * @param string $entityClass
     * @param EntityFilter[] $filters
     * @return void
     * @throws ReflectionException
     * @throws Exception
     */
    public static function deleteEntity(string $entityClass, array $filters): void
    {
        global $wpdb;

        if (count($filters) == 0) {
            throw new  Exception("No filters were specified");
        }

        self::createFilters($entityClass, $filters);
        $filterArray = [];
        $templateArray = [];
        foreach ($filters as $filter) {
            $filterArray[$filter->columnName] = $filter->value;
            $templateArray[] = $filter->placeholderType;
        }

        $tableDefinition = self::getTableDefinition($entityClass);
        $result = $wpdb->delete(
            $tableDefinition->getPrefixedTableName(),
            $filterArray,
            $templateArray,
        );

        if (!$result) {
            throw new Exception("An error occured during delete $wpdb->error");
        }
    }

    /**
     * @throws Exception
     */
    public static function startTransaction(): void
    {
        global $wpdb;
        $result = $wpdb->query("START TRANSACTION");
        if ($result != 0) {
            throw new Exception("An error occured while starting transaction");
        }
    }

    /**
     * @throws Exception
     */
    public static function commitTransaction(): void
    {
        global $wpdb;
        $result = $wpdb->query("COMMIT");
        if ($result != 0) {
            throw new Exception("An error occured while commiting transaction");
        }
    }

    /**
     * @throws Exception
     */
    public static function rollbackTransaction(): void
    {
        global $wpdb;
        $result = $wpdb->query("ROLLBACK");
        if ($result != 0) {
            throw new Exception("An error occured while rollbacking transaction");
        }
    }

    /**
     * @return RoomListRequest[]
     * @throws ReflectionException
     */
    public static function getRoomsWithImage(): array
    {
        //TODO: Make global joining framework instead
        global $wpdb;
        $sql = "
            SELECT
                %s
            FROM
                %s
            INNER JOIN %s ON %s = %s AND %s = %d";

        $roomTable = self::getTableDefinition(Room::class);
        $imageTable = self::getTableDefinition(RoomImage::class);

        $selectorArgs = join(", ", [
            "r." . Room::ID_INT,
            "r." . Room::NAME_STR,
            "r." . Room::DESCRIPTION_STR,
            "i." . RoomImage::POST_ID_INT
        ]);

        $sql = sprintf($sql,
            $selectorArgs,
            $roomTable->getPrefixedTableName() . " as r",
            $imageTable->getPrefixedTableName() . " as i",
            "i." . RoomImage::ROOM_ID_INT,
            "r." . Room::ID_INT,
            "i." . RoomImage::PRIORITY_INT,
            0
        );

        $rows = $wpdb->get_results($sql);
        $dtos = [];
        foreach ($rows as $row) {
            $dto = RoomListRequest::createDTO();
            ObjectMapper::toDTO($row, $dto, [
                Room::ID_INT => RoomListRequest::ID_INT,
                Room::NAME_STR => RoomListRequest::NAME_STR,
                Room::DESCRIPTION_STR => RoomListRequest::DESCRIPTION_STR,
            ]);
            ObjectMapper::toDTO($row, $dto, [
                RoomImage::POST_ID_INT => RoomListRequest::IMAGE_POST_ID_INT
            ]);
            $dtos[] = $dto;
        }

        return $dtos;
    }

    //<editor-fold desc="Mapping">

    /**
     * @param string $entityClass
     * @param array<string, mixed> $row
     * @return object
     * @throws ReflectionException
     */
    protected static function mapRowToObject(string $entityClass, array $row): object
    {
        $object = new $entityClass();
        /** @var $columns array<string, DBColumnAttribute> */
        $columns = self::getColumnDefinitions($entityClass);

        foreach ($columns as $objectConst => $columnAttribute) {
            if (isset($row[$columnAttribute->ColumnName])) {
                $object->{$objectConst} = $row[$columnAttribute->ColumnName];
            }
        }

        return $object;
    }
    //</editor-fold>

    /**
     * @param string $entityClass
     * @param $includeColumns string[] array will include all columns. Should use the value provided by entity constants.
     * @return string
     * @throws ReflectionException
     */
    public static function getColumnSelector(string $entityClass, array $includeColumns): string
    {
        $columnSelector = "*";
        if (count($includeColumns) > 0) {
            /** @var $columnDefinitions array<string, DBColumnAttribute> */
            $columnDefinitions = self::getColumnDefinitions($entityClass);
            foreach ($includeColumns as $includeColumn) {
                if (isset($columnDefinitions[$includeColumn])) {
                    $columnSelector = join(", ", [$columnSelector, $columnDefinitions[$includeColumn]->ColumnName]);
                }
            }
        }

        return $columnSelector;
    }

    /**
     * @param string $entityClass
     * @param EntityFilter[] $filters
     * @return array
     * @throws ReflectionException
     */
    public static function createFilters(string $entityClass, array $filters): array
    {
        $columnDefinitions = self::getColumnDefinitions($entityClass);
        foreach ($filters as $filter) {
            if (isset($columnDefinitions[$filter->columnName])) {
                $filterPlaceholder = $columnDefinitions[$filter->columnName]->ColumnTemplateType;
                $filter->setPlaceholder($filterPlaceholder);
            }
        }

        return $filters;
    }

    //<editor-fold desc="Get attributes">

    /**
     * @param string $className
     * @return array<string, DBColumnAttribute>
     * @throws ReflectionException
     */
    protected static function getColumnDefinitions(string $className): array
    {
        $reflectedClass = new ReflectionClass($className);
        /** @var $classConstants array<string, string> */
        $classConstants = $reflectedClass->getConstants();
        if (empty($classConstants)) {
            return [];
        }

        $resultArray = [];
        foreach ($classConstants as $classConstant => $value) {
            $reflectedConstant = $reflectedClass->getReflectionConstant($classConstant);
            $reflectedAttributes = $reflectedConstant->getAttributes();

            if (isset($reflectedAttributes[0])) {
                $instance = $reflectedAttributes[0]->newInstance();
                $resultArray[$value] = $instance;
            }
        }

        return $resultArray;
    }

    /**
     * @return DBTableAttribute
     * @throws Exception
     * @throws ReflectionException
     */
    protected static function getTableDefinition(string $className): object
    {
        $reflectedClass = new ReflectionClass($className);
        $classAttributes = $reflectedClass->getAttributes(DBTableAttribute::class);
        if (count($classAttributes) === 0) {
            throw new Exception("Class {$className} does not have a DBTableAttribute");
        }

        return $classAttributes[0]->newInstance();
    }
    //</editor-fold>


}