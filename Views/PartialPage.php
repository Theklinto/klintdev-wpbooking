<?php

namespace KlintDev\WPBooking\Views;

use Exception;

abstract class PartialPage
{
    /**
     * @return PartialPage
     */
    abstract public static function getInstance(): object;

	/**
	 * @return string|false
	 * @throws Exception
	 */
    abstract public static function render(): string|false;

    /** @return ContentDependency[] */
    abstract public static function getRequiredContent(): array;

    protected static function inlineScript(): string|false
    {
        return "";
    }

    protected static function inlineStyle(): string|false
    {
        return "";
    }
}