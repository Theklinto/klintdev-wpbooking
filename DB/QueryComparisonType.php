<?php /** @noinspection PhpUnused */

namespace KlintDev\WPBooking\DB;

enum QueryComparisonType: string
{
    case EQUAL = '=';
    case GREATER = '>';
    case LESS = '<';
    case NOT_EQUAL = '!=';
    case GREATER_EQUAL = '>=';
    case LESS_EQUAL = '<=';
    case IS_NOT = "IS NOT";
    case IS = "IS";

}