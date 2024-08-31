<?php

namespace KlintDev\WPBooking\Controllers;

use Exception;
use KlintDev\WPBooking\Attributes\RouteAttribute;
use KlintDev\WPBooking\GlobalSettings;
use KlintDev\WPBooking\Interfaces\IController;
use KlintDev\WPBooking\Logging\Logger;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;

class ControllerInitializer {
	public static function registerRestEndpoints(): bool {
		try {
			Logger::log_info( "Starting to register rest endpoints" );
			//Require all controllers
			foreach ( glob( KDWPB_PATH . "[Cc]ontrollers/*[Cc]ontroller.php" ) as $file ) {
				require_once $file;
			}


			//Find all classes that implements IController
			$controllers = [];
			foreach ( get_declared_classes() as $class ) {
				if ( in_array( IController::class, class_implements( $class ) ) ) {
					$controllers[] = $class;
				}
			}

			$routeAttributeFilter = fn( ReflectionAttribute $attr ) => $attr->getName() === RouteAttribute::class;

			foreach ( $controllers as $controller ) {
				$class                     = new ReflectionClass( $controller );
				$controllerRouteAttributes = array_filter( $class->getAttributes(), $routeAttributeFilter );
				if ( count( $controllerRouteAttributes ) > 0 ) {
					$controllerRoute = $controllerRouteAttributes[0]->newInstance();
					$methods         = $class->getMethods( ReflectionMethod::IS_PUBLIC );
					foreach ( $methods as $method ) {
						$methodRoutes = array_filter( $method->getAttributes(), $routeAttributeFilter );
						if ( count( $methodRoutes ) > 0 ) {
							$methodRoute   = $methodRoutes[0]->newInstance();
							$endpointRoute = $controllerRoute->Route . "/" . $methodRoute->Route;

							register_rest_route(
								GlobalSettings::API_BASE_PATH,
								"/" . $endpointRoute,
								[
									"methods"             => $methodRoute->Method,
									'callback'            => [ $class->newInstance(), $method->getName() ],
									'permission_callback' => $methodRoute->RequiredAdmin ?
										fn() => current_user_can( GlobalSettings::PLUGIN_CAPABILITY ) :
										'__return_true',
								]
							);
						}
					}
				}
			}

			Logger::log_info( "Restend endpoints finished" );

			return true;
		} catch ( Exception $e ) {
			Logger::log_error( "Failed to register rest endpoints", [ "Exception" => $e->getMessage() ] );

			return false;
		}
	}

}

