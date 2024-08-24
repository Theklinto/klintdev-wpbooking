<?php

namespace KlintDev\WPBooking\DB;

class EntityFilter
{
    protected string $queryPlaceholderString = "";
    protected string $queryValue = "";
    public string $placeholderType = "";

    public function __construct(
        public readonly string              $columnName,
        public readonly QueryComparisonType $comparisonType,
        public readonly mixed               $value,
    )
    {
    }

    public function setPlaceholder(string $placeholderType): void
    {
        $this->placeholderType = $placeholderType;
        $this->queryPlaceholderString = "{$this->columnName} {$this->comparisonType->value} {$placeholderType}";
    }

    public function getQueryPlaceholderString(): string
    {
        return $this->queryPlaceholderString;
    }

    public function getQueryValue(): string
    {
        return $this->queryValue;
    }
}