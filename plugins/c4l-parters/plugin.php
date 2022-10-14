<?php

/*
Plugin Name: Call4Life partners
Plugin URI: http://google.com
Description: integration partners
Version: 0.0.1
Author: Call4life
Author URI: https://github.com/lexus27
License: MIT License
*/

// add_action('wp_loaded', 'redirect_to_plugin_page');
// function redirect_to_plugin_page()
// {
//     if (isset($_POST['subscribe'])) {
//         $redirect = 'http://localhost/wordpress/wp-admin/admin.php?page=c4l-parters-top-level-slug';
//         wp_redirect($redirect);
//         exit;
//     }
// }

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Constants.php';

/**
 * Include Exceptions
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Exceptions/C4lException.php';

/** 
 * For migration during activate and diactivate of plugin 
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Database/OnActivationPluginMigration.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Database/OnDiactivationPluginMigration.php';
/** 
 * Include restfull api 
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . "Services/ApiPartnerService.php";
/** 
 * Include service
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Services/PartnerCsvService.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Services/ClientCsvService.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Services/PartnerService.php';
/**
 * Include views
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . "Views/BaseView.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Views/Shared/PaginationView.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Views/Partner/Admin/PartnerIndexView.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Views/Partner/Admin/PartnerEditView.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Views/Partner/Admin/PartnerDeleteView.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Views/Partner/Admin/PartnerEditAddClientsView.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Views/Partner/Admin/PartnerCreateView.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Views/Partner/PartnerLoginView.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Views/Partner/PartnerProfileView.php";
/** 
 * Include enities 
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . "Models/IBaseModel.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Models/Client.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Models/Partner.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Models/Pagination.php";
/** 
 * Include repositories 
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . "Repositories/BaseRepository.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Repositories/ClientRepository.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Repositories/PartnerRepository.php";


use C4lPartners\Database\CreateMigration;
use C4lPartners\Database\DropMigration;
use C4lPartners\Repositories\ClientRepository;
use C4lPartners\Repositories\PartnerRepository;
use C4lPartners\Services\ApiPartnerService;
use C4lPartners\Services\ClientCsvService;
use C4lPartners\Services\PartnerService;
use C4lPartners\Views\Partner\PartnerCreateView;
use C4lPartners\Views\Partner\PartnerDeleteView;
use C4lPartners\Views\Partner\PartnerEditAddClientsView;
use C4lPartners\Views\Partner\PartnerEditView;
use C4lPartners\Views\Partner\PartnerIndexView;

$dbCreater = new CreateMigration();
$dbDropper = new DropMigration();

register_activation_hook(__FILE__, array($dbCreater, 'CreateTablesInDataBase'));
// register_deactivation_hook(__FILE__, array($dbDropper, 'DropTablesInDataBase'));
// register_uninstall_hook(__FILE__, array($dbDropper, 'DropTablesInDataBase'));

$partnerRepository = new PartnerRepository();
$clientRepository = new ClientRepository();
$clientCsvService = new ClientCsvService($clientRepository);

$parterService = new PartnerService(
    new PartnerIndexView(),
    new PartnerCreateView(),
    new PartnerEditView(),
    new PartnerDeleteView(),
    new PartnerEditAddClientsView(),

    $partnerRepository,

    $clientCsvService,
);

/**
 * Logic for main menu in admin panel
 */
add_action('admin_menu', array($parterService, 'SetParterLogicForAdminMenu'));

/**
 * Logic for submenu in admin panel
 */
add_action('admin_menu', array($parterService, 'SetParterLogicForAdminSubMenu'));


add_shortcode('c4l_partner_list', array($parterService, 'PartnerListShortCode'));



$apiPartnerService = new ApiPartnerService($partnerRepository);

/**
 * Register the RESTFULL API
 */
add_action('rest_api_init',  array($apiPartnerService, 'Init'));
