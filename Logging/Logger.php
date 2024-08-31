<?php

namespace KlintDev\WPBooking\Logging;

use DateTime;
use KlintDev\WPBooking\GlobalSettings;

class Logger {

	protected const LOGFILE_FORMAT = "log-%date%.log";
	protected const LOGENTRY_FORMAT = "[%dateTime% - %level%] %message%";

	public static function log( LoggingLevel $level, $message, array $params = [] ): void {
		$minLoggingLevel = self::getLoggingLevel();
		if ( ! isset( $minLoggingLevel ) || $minLoggingLevel > $level || $minLoggingLevel === LoggingLevel::NONE ) {
			return;
		}
		$logPath = self::getLoggingPath();
		if ( ! isset( $logPath ) || strlen( $logPath ) === 0 ) {
			return;
		}

		$paramsMessage = [];
		foreach ( $params as $key => $value ) {
			$paramsMessage[] = "\t$key => $value";
		}

		$date = new DateTime();

		$message = join( "\n", [ $message, ...$paramsMessage ] );
		$message = str_replace(
			[
				"%dateTime%",
				"%level%",
				"%message%"
			],
			[
				$date->format( 'Y-m-d H:i:s' ),
				$level->name,
				$message
			],
			self::LOGENTRY_FORMAT
		);
		if ( ! str_ends_with( $message, "\n" ) ) {
			$message .= "\n";
		}

		$filename = join( "\\", [ $logPath, self::LOGFILE_FORMAT ] );
		$filename = str_replace(
			[
				"%date%"
			],
			[
				$date->format( 'Y-m-d' ),
			],
			$filename
		);
		file_put_contents(
			$filename,
			$message,
			FILE_APPEND
		);
	}

	public static function log_debug( $message, array $params = [] ): void {
		self::log( LoggingLevel::DEBUG, $message, $params );
	}

	public static function log_info( $message, array $params = [] ): void {
		self::log( LoggingLevel::INFO, $message, $params );
	}

	public static function log_error( $message, array $params = [] ): void {
		self::log( LoggingLevel::ERROR, $message, $params );
	}

	public static function log_warning( $message, array $params = [] ): void {
		self::log( LoggingLevel::WARNING, $message, $params );
	}


	public static function setup(
		LoggingLevel $minLoggingLevel = LoggingLevel::INFO,
		?string $logPath = KDWPB_PATH . "logs"
	): void {
		$logPath = str_replace( "/", "\\", $logPath );
		wp_mkdir_p( $logPath );
		update_option( self::LOGGING_PATH_OPTION, $logPath, true );
		update_option( self::LOGGING_LEVEL_OPTION, $minLoggingLevel->value, true );
	}

	protected const LOGGING_LEVEL_OPTION = GlobalSettings::PLUGIN_PREFIX . "_logging_level";
	protected const LOGGING_PATH_OPTION = GlobalSettings::PLUGIN_PREFIX . "_logging_path";

	public static function getLoggingLevel(): LoggingLevel|null {
		/** @var LoggingLevel|false $loggingLevel */
		$loggingLevel = get_option( self::LOGGING_LEVEL_OPTION );
		$loggingLevel = LoggingLevel::tryFrom( $loggingLevel );
		if ( $loggingLevel === null ) {
			error_log( "Failed to get logging level from WP Options" );

			return null;
		}

		return $loggingLevel;
	}

	public static function setLoggingLevel( LoggingLevel $loggingLevel ): void {
		$result = update_option( self::LOGGING_LEVEL_OPTION, $loggingLevel->value, true );
		if ( $result === false ) {
			self::log_error( "Failed to set logging level to WP Options" );
		}

		self::log_debug( "Logging level has been updated", [ "Logging Level" => $loggingLevel ] );
	}

	public static function getLoggingPath(): string|null {
		$result = get_option( self::LOGGING_PATH_OPTION );
		if ( $result === false ) {
			error_log( "Failed to get logging path from WP Options" );

			return null;
		}

		return $result;
	}

	public static function setLoggingPath( string $logPath ): void {
		$result = update_option( self::LOGGING_PATH_OPTION, $logPath, true );
		if ( $result === false ) {
			self::log_error( "Failed to set logging path to WP Options" );
		}

		self::log_debug( "Logging path has been updated", [ "path" => $logPath ] );
	}
}