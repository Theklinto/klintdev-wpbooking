<?php

namespace KlintDev\WPBooking\Controllers;

use Exception;
use KlintDev\WPBooking\Attributes\RouteAttribute;
use KlintDev\WPBooking\DTO\Package\PackageCreateRequest;
use KlintDev\WPBooking\DTO\Package\PackageUpdateRequest;
use KlintDev\WPBooking\Interfaces\IController;
use KlintDev\WPBooking\Services\PackageService;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

#[RouteAttribute( self::CONTROLLER_PREFIX )]
class PackageController extends ControllerBase implements IController {
	protected const CONTROLLER_PREFIX = "package";
	public const UPDATE_ENDPOINT = "update";
	public const CREATE_ENDPOINT = "create";
	public const DELETE_ENDPOINT = "delete";

	#[RouteAttribute( self::CREATE_ENDPOINT, WP_REST_Server::CREATABLE, true )]
	public function createPackage( WP_REST_Request $request ): WP_REST_Response {
		try {
			$params = $request->get_json_params();
			/** @var $dto */
			$dto = PackageCreateRequest::dtoFromArray( $params );
			$dto->validate();
			PackageService::createPackage( $dto );


			return new WP_REST_Response( [], 200 );
		} catch ( Exception $e ) {
			return new WP_REST_Response( $e->getMessage(), 500 );
		}
	}

	#[RouteAttribute( self::UPDATE_ENDPOINT, WP_REST_Server::EDITABLE, true )]
	public function updatePackage( WP_REST_Request $request ): WP_REST_Response {
		try {
			$params = $request->get_json_params();
			$dto    = PackageUpdateRequest::dtoFromArray( $params );
			$dto->validate();
			PackageService::updatePackage( $dto );

			return new WP_REST_Response( [], 200 );
		} catch ( Exception $e ) {
			return new WP_REST_Response( $e->getMessage(), 500 );
		}
	}

	#[RouteAttribute( self::DELETE_ENDPOINT, WP_REST_Server::DELETABLE, true )]
	public function deletePackage( WP_REST_Request $request ): WP_REST_Response {
		try {
			$id = $request->get_param( 'id' );
			if ( ! isset( $id ) ) {
				throw new Exception( 'No packageId provided' );
			}
			PackageService::deletePackage( $id );

			return new WP_REST_Response( [], 200 );
		} catch ( Exception $e ) {
			return new WP_REST_Response( $e->getMessage(), 500 );
		}
	}

	public static function getEndpointUrl( string $method, array $queryParams = [] ): string {
		return self::baseGetEndpointUrl( self::CONTROLLER_PREFIX, $method, $queryParams );
	}
}