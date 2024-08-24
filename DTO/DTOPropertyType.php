<?php

namespace KlintDev\WPBooking\DTO;

enum DTOPropertyType: string
{
    case String = "string";
    case Int = "integer";
    case Float = "double";
    case Bool = "boolean";
    case Array = "array";
}