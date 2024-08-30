<?php

namespace KlintDev\WPBooking\Utilities;

class Regex{
	/**
	 * Matches a timestamp between 00:00 and 23:59
	 * @type string
	 */
	public const TIMESTAMP = "/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/";
}
