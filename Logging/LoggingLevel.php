<?php

namespace KlintDev\WPBooking\Logging;

enum LoggingLevel: int {
	case DEBUG = 0;
	case INFO = 1;
	case WARNING = 2;
	case ERROR = 3;
	case NONE = 4;
}