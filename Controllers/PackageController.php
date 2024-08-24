<?php
//
//namespace KlintDev\WPBooking\Controllers;
//
//use Exception;
//use KlintDev\WPBooking\Interfaces\IController;
//use WP_REST_Request;
//use WP_REST_Response;
//use WP_REST_Server;
//
//require_once KDWPB_PATH . "db/package_db.php";
//require_once KDWPB_PATH . "models/blocked_duration_model.php";
//
//#[Route("package")]
//class PackageController implements IController
//{
//    #[Route("create", WP_REST_Server::CREATABLE, true)]
//    public function create_blocked_duration(WP_REST_Request $request): WP_REST_Response
//    {
//        try {
//            $model = new PackageModel();
//            Utilities::json_decode_to_class($request->get_body(), $model);
//
//            PackageDB::create_package($model);
//            return new WP_REST_Response(null, 200);
//        } catch (Exception $e) {
//            return new WP_REST_Response($e->getMessage(), 500);
//        }
//    }
//
//    #[Route("all", WP_REST_Server::READABLE, true)]
//    public function list(WP_REST_Request $request): WP_REST_Response
//    {
//        try {
//            $packages = PackageDB::list();
//            return new WP_REST_Response($packages, 200);
//        } catch (Exception $e) {
//            return new WP_REST_Response($e->getMessage(), 500);
//        }
//    }
//
//    #[Route("getById", WP_REST_Server::READABLE, true)]
//    public function getPackage(WP_REST_Request $request): WP_REST_Response
//    {
//        try {
//
//            $id = $request->get_param('id');
//            if (empty($id)) {
//                return new WP_REST_Response(null, 404);
//            }
//
//            $package = PackageDB::get_by_id($id);
//            return new WP_REST_Response($package);
//        } catch (Exception $e) {
//            return new WP_REST_Response($e->getMessage(), 500);
//        }
//    }
//
//    #[Route("update", WP_REST_Server::EDITABLE, true)]
//    public function updatePackage(WP_REST_Request $request): WP_REST_Response
//    {
//        try {
//            $packageModel = new PackageModel();
//            Utilities::json_decode_to_class($request->get_body(), $packageModel);
//
//            PackageDB::update_package($packageModel);
//
//            return new WP_REST_Response(null, 200);
//        } catch (Exception $e) {
//            return new WP_REST_Response($e->getMessage(), 500);
//        }
//    }
//}