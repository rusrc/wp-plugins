<?php

namespace C4lPartners\Services;

use C4lPartners\C4lException\C4lException;
use C4lPartners\Constants;
use C4lPartners\Models\Partner;
use C4lPartners\Repositories\PartnerRepository;
use C4lPartners\Views\Partner\PartnerCreateView;
use C4lPartners\Views\Partner\PartnerDeleteView;
use C4lPartners\Views\Partner\PartnerEditAddClientsView;
use C4lPartners\Views\Partner\PartnerIndexView;
use C4lPartners\Views\Partner\PartnerEditView;
use C4lPartners\Views\PartnerLoginView;

// TODO rename to router
/**
 * User for admin menu
 */
class PartnerService
{
    private PartnerIndexView $_parterIndexView;
    private PartnerCreateView $_partnerCreateView;
    private PartnerEditView $_partnerEditView;
    private PartnerDeleteView $_partnerDeleteView;
    private PartnerEditAddClientsView $_partnerEditAddClientsView;

    private PartnerRepository $_partnerRepository;

    private ClientCsvService $_clientCsvService;

    public function __construct(
        /** Views */
        PartnerIndexView $partnerIndexView,
        PartnerCreateView $partnerCreateView,
        PartnerEditView $partnerEditView,
        PartnerDeleteView $partnerDeleteView,
        PartnerEditAddClientsView $partnerEditAddClientsView,
        /** Repository */
        PartnerRepository $partnerRepository,
        /** Service */
        ClientCsvService $clientCsvService
    ) {
        $this->_parterIndexView = $partnerIndexView;
        $this->_partnerCreateView = $partnerCreateView;
        $this->_partnerEditView = $partnerEditView;
        $this->_partnerDeleteView = $partnerDeleteView;
        $this->_partnerEditAddClientsView = $partnerEditAddClientsView;

        $this->_partnerRepository = $partnerRepository;

        $this->_clientCsvService = $clientCsvService;
    }


    /**
     * Set Parter Logic For AdminMenu
     *
     * @return void
     */
    public function SetParterLogicForAdminMenu()
    {
        add_menu_page(
            'Партнёры C4L 2',                 // Текст, который будет использован в теге <title> на странице, относящейся к пункту меню.
            'Партнёры 2',                     // Название пункта меню в сайдбаре админ-панели.
            'manage_options',                 // Права пользователя (возможности), необходимые чтобы пункт меню появился в списке. Таблицу возможностей смотрите здесь.
            Constants::C4l_ADMIN_PAGE,        // Уникальное название (slug), по которому затем можно обращаться к этому меню
            array($this, 'AdminLogicInit'),      // Название функции, которая выводит контент страницы пункта меню.
            'dashicons-edit'                  // Иконка для пункта меню.
        );
    }

    /**
     * Set Parter Logic For Admin SubMenu
     *
     * @return void
     */
    public function SetParterLogicForAdminSubMenu()
    {
        add_submenu_page(
            Constants::C4l_ADMIN_PAGE,
            'Общие настройки',
            'Общие настройки',
            'manage_options',
            'c4l-parterns-low-level-slug-1',
            array($this, 'PartnerSettings')
        );
    }

    public function PartnerListShortCode()
    {

        var_dump($_GET);

        if ($parnterId = $_GET['partnerId']) {

            
            if($partner = $_POST['partner']){
                var_dump($partner);
            }

            $partner = $this->_partnerRepository->GetItem($parnterId);

            PartnerLoginView::Render($partner);

        } else {

            $partners = $this->_partnerRepository->GetAll();

            echo "<div class='container'>";
            echo "  <div class='row'>";
            echo "      <div class='col'>";

            foreach ($partners as $partner) {
                echo "<p><a href='?partnerId={$partner->id}'>{$partner->name}</a></p>";
            }

            echo "      </div>";
            echo "  </div>";
            echo "</div>";
        }
    }

    function new_title()
    {
        return 'New title';
    }


    function AdminLogicInit()
    {
        // Error handler
        try {
            $this->PartnerAdminRouterInit();
        } catch (C4lException $ex) {
            echo "<p style='color: red;'>";
            echo "Ошибка: " . $ex->getMessage();
            echo "<br />";
            echo "<a href='{$this->_parterIndexView->admin_root_url()}'>Назад к списку</a>";
            echo "</p>";
        } catch (\Throwable $ex) {
            throw $ex;
        }
    }

    function PartnerAdminRouterInit()
    {
        // Router
        if ($_GET['create_partner']) {

            // Create item page
            $isSuccess = false;
            if ($params = $_POST['partner']) {
                $model = new Partner($params);
                $isSuccess = $this->_partnerRepository->Save($model) > 0;
            }

            $this->_partnerCreateView->Render(new Partner(), $isSuccess);
        } else if (($partnerId = $_GET['edit_partner_by_id']) && $_GET['tab'] === 'general_tab') {

            // Edit item page client_tab
            $isSuccess = false;
            if ($params = $_POST['partner']) {
                $model = new Partner($params);
                $isSuccess = $this->_partnerRepository->Update($model) > 0;
            }

            $partner = $this->_partnerRepository->GetItem($partnerId);
            $this->_partnerEditView->Render($partner, $isSuccess);
        } else if (($partnerId = $_GET['edit_partner_by_id']) && $_GET['tab'] === 'client_tab') {

            // Edit item page general_tab
            $isSuccess = false;
            if ($file = $_FILES['csv-file']) {
                $isSuccess = $this->_clientCsvService->UploadClientsFromCsv($file, $partnerId);
            }

            $partner = $this->_partnerRepository->GetItem($partnerId);
            $this->_partnerEditAddClientsView->Render($partner,  $isSuccess);
        } else if ($partnerId = $_GET['delete_partner_by_id']) {

            // Delete item page
            $isSuccess = false;
            $partner = $this->_partnerRepository->GetItem($partnerId);
            if ($_POST['partner']) {
                $isSuccess = $this->_partnerRepository->Delete($partner) > 0;
            }

            $this->_partnerDeleteView->Render($partner, $isSuccess);
        } else if ($pageNumber = $_GET['pageNumber']) {

            // Pagination and search
            if ($search = $_GET['search']) {
                // TODO search
                echo $search;
            }

            $partners = $this->_partnerRepository->GetItemsByPageNumber(Constants::PAGE_SIZE, $pageNumber);
            $pagination = $this->_partnerRepository->GetPagination(Constants::PAGE_SIZE, $pageNumber);

            $this->_parterIndexView->Render($partners, $pagination);
        } else {

            $partners = $this->_partnerRepository->GetItemsByPageNumber(Constants::PAGE_SIZE);
            $pagination = $this->_partnerRepository->GetPagination(Constants::PAGE_SIZE);

            $this->_parterIndexView->Render($partners, $pagination);
        }
    }

    function PartnerSettings()
    {
        $partner = $this->_partnerRepository->GetItem(1);

        echo $partner->id;
    }
}
