<?php

namespace KlintDev\WPBooking\DTO;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class DTOPropertyAttribute
{
    public string $PropertyName;
    public DTOPropertyType $PropertyType;
    public mixed $DefaultValue;
    public bool $Nullable;

    public function __construct(string $propertyName, DTOPropertyType $propertyType, mixed $defaultValue, bool $nullable = false)
    {
        $this->PropertyName = $propertyName;
        $this->PropertyType = $propertyType;
        $this->DefaultValue = $defaultValue;
        $this->Nullable = $nullable;
    }
}