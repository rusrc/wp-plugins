<?php

namespace C4lPartners\Views\Partner;

use C4lPartners\Models\Client;
use C4lPartners\Models\Partner;
use C4lPartners\Views\BaseView;

class PartnerEditAddClientsView extends BaseView
{
    public function Render(Partner $partner, bool $isUploaded = false)
    {
?>
        <h1>Редактировать компанию</h1>

        <p><a href="<?= $this->admin_root_url() ?>">Назад к списку</a></p>

        <div>
            <nav class="health-check-tabs-wrapper hide-if-no-js" aria-label="Secondary menu">
                <a href="<?= $this->add_query_arg("edit_partner_by_id=$partner->id&tab=general_tab", '') ?>" class="health-check-tab">
                    Настройки </a>

                <a href="<?= $this->add_query_arg("edit_partner_by_id=$partner->id&tab=client_tab", '') ?>" class="health-check-tab active" aria-current="true">
                    Клиенты </a>
            </nav>
        </div>
        <div>

            <?php if ($isUploaded) : ?>

                <h3 class="alert">Файл загружен</h3>

            <?php endif; ?>

            <h3>Загрузить CSV</h3>
            <input type="hidden" name="partner[id]" value="<?= $partner->id ?>">
            <form enctype="multipart/form-data" method="POST">
                <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                <input name="csv-file" type="file" />
                <input class="button button-primary" type="submit" value="Загрузить CSV файл в базу" />
            </form>

            <br>
            <?php if (isset($partner->clients)) : ?>
                <table>
                    <tr>
                        <?php foreach (get_object_vars(new Client()) as $key => $value) : ?>
                            <th><?= $key ?></th>
                        <?php endforeach; ?>
                    </tr>
                    <?php foreach ($partner->clients as $client) : ?>
                        <tr>
                            <td><?= $client->id ?></td>
                            <td><?= $client->name ?></td>
                            <td><?= $client->login ?></td>
                            <td><?= $client->password ?></td>
                            <td><?= $client->partner_id ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>

        </div>

        <style>
            .health-check-tabs-wrapper {
                display: -ms-inline-grid;
                -ms-grid-columns: 1fr 1fr;
                vertical-align: top;
                display: inline-grid;
                grid-template-columns: 1fr 1fr;
            }

            .health-check-header {
                text-align: center;
                margin: 0 0 1rem;
                background: #fff;
                border-bottom: 1px solid #e2e4e7;
            }

            .health-check-tab.active {
                box-shadow: inset 0 -3px #007cba;
                font-weight: 600;
            }

            .health-check-tab {
                display: block;
                text-decoration: none;
                color: inherit;
                padding: .5rem 1rem 1rem;
                /* margin: 0 1rem; */
                transition: box-shadow .5s ease-in-out;
            }

            .c4l-create-new-item-table td {
                margin: 0;
                padding: 0;
            }

            /** Styles */
            .qtranxs-lang-switch-wrap {
                display: none;
            }

            .policySearch {
                margin: 30px 0 10px 0;
            }

            table {
                width: 70%;
            }

            table tbody tr:nth-of-type(odd) {
                background-color: rgba(0, 0, 0, .05);
            }

            table thead tr th {
                padding: 15px 0;
            }

            table tbody tr td {
                text-align: center;
                padding: 5px 0;
            }

            a.edit {
                margin-right: 40px;
            }

            table tbody trtable__pageNomber {
                background: none;
            }

            table tbody table__pageNomber table__pageLink {
                width: 20px;
                height: 20px;
                display: inline-block;
                background: #d8d8d8;
                border-radius: 5px;
                padding: 5px;
                text-align: center;
                text-decoration: none;
                color: #000;
                margin-bottom: 10px;
            }

            table tbody table__pageNomber table__pageLink.active,
            table tbody table__pageNomber table__pageLink:hover {
                background: #0073aa;
                color: #fff;
            }

            .alert {
                background: cadetblue;
                color: white;
                padding: 15px;
                max-width: 200px;
                text-align: center;
                box-shadow: 1px solic black;
                box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
                -webkit-box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
            }
        </style>

<?php
    }
}
