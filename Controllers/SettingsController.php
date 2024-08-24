<?php

namespace KlintDev\WPBooking\Controllers;

use Exception;
use KlintDev\WPBooking\Attributes\RouteAttribute;
use KlintDev\WPBooking\DTO\StripeSettings\GetStripeSettingsRequest;
use KlintDev\WPBooking\DTO\StripeSettings\UpdateStripeSettingsRequest;
use KlintDev\WPBooking\GlobalSettings;
use KlintDev\WPBooking\Interfaces\IController;
use KlintDev\WPBooking\Services\OptionService;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

#[RouteAttribute(SettingsController::CONTROLLER_PREFIX)]
class SettingsController extends ControllerBase implements IController
{
    public const CONTROLLER_PREFIX = "settings";
    public const UPDATE_STRIPE_SETTINGS_ENDPOINT = "stripe/update";
    public const GET_STRIPE_SETTINGS_ENDPOINT = "stripe/settings";


    #[RouteAttribute(self::UPDATE_STRIPE_SETTINGS_ENDPOINT, WP_REST_Server::EDITABLE, true)]
    public function updateStripeSettings(WP_REST_Request $request): WP_REST_Response
    {
        $result = $request->get_body_params();
        $dto = UpdateStripeSettingsRequest::dtoFromArray($request->get_body_params());

        $previousKey = OptionService::stripeApiKey();
        $newKey = $dto->getPropertyValue($dto::API_KEY);
        $previousSecret = OptionService::stripeApiSecret();
        $newSecret = $dto->getPropertyValue($dto::API_SECRET);

        $error = [];

        if ($previousKey !== $newKey) {
            $keyUpdated = OptionService::stripeApiKey($newKey);
            if (!$keyUpdated) {
                $error[] = "Stripe API Key kunne ikke opdateres!";
            }
        }
        if ($previousSecret !== $newSecret) {
            $secretUpdated = OptionService::stripeApiSecret($newSecret);
            if (!$secretUpdated) {
                $error[] = "Stripe API Secret kunne ikke opdateres!";
            }
        }

        if (count($error) > 0) {
            return new WP_REST_Response(join("\n", $error), 500);
        }

        return new WP_REST_Response([]);
    }

//    #[RouteAttribute(self::GET_STRIPE_SETTINGS_ENDPOINT, WP_REST_Server::READABLE, true)]
//    public function getStripeSettings(WP_REST_Request $request): WP_REST_Response
//    {
//        try {
//
//            $dto = GetStripeSettingsRequest::createDTO();
//
//            $dto::assignPropertyValue($dto, $dto::API_KEY, get_option(self::API_KEY_OPTION));
//            $dto::assignPropertyValue($dto, $dto::API_SECRET, get_option(self::API_SECRET_OPTION));
//
//            return new WP_REST_Response($dto, 200);
//        } catch (Exception $exception) {
//            return new WP_REST_Response($exception->getMessage(), 500);
//        }
//
//    }

    public static function getEndpointUrl(string $method, array $queryParams = []): string
    {
        return self::baseGetEndpointUrl(self::CONTROLLER_PREFIX, $method);
    }
}