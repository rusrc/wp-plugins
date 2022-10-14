<?php

namespace C4lPartners\Views\Partner;

use C4lPartners\Models\Pagination;
use C4lPartners\Views\BaseView;
use C4lPartners\Views\PaginationView;

class PartnerIndexView extends BaseView
{
    public function Render($partners, Pagination $pagination = null)
    {
?>
        <br>
        <a href="<?= $this->add_query_arg('create_partner=true', '') ?>">Добавить партнерскую компанию</a>

        <h3>Список партнерских компаний</h3>

        <!-- <div>
            <form>
                <input type="hidden" name="page" value="c4l-policy-aggregator/policy-aggregator.php">
                <input type="text" placeholder="Поиск..." name="search" value=""> <button class="button button-primary">Найти</button>
                <div style="color:red;">Поиск в разработке</div>
            </form>
        </div> -->
        <table class="policyTable">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Название</th>
                    <th>Ссылка</th>
                    <th>Ссылка на логотип</th>
                    <th>Текст приветствия</th>
                    <th>Название логина (для поля на форме)</th>
                    <th>Название пароля (для поля на форме)</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($partners as $partner) : ?>

                    <tr>
                        <?php foreach (get_object_vars($partner) as $value) : ?>
                            <td><?= strlen($value) > 150 ? substr($value, 0, 150) . "..." : $value ?></td>
                        <?php endforeach; ?>

                        <td>
                            <a href="<?= $this->admin_add_to_root_url("edit_partner_by_id=$partner->id&tab=general_tab", '') ?>">Редактировать</a>
                            <a href="<?= $this->admin_add_to_root_url("delete_partner_by_id=$partner->id", '') ?>">Удалить</a>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>

        <div>Всего: <?= count($partners) ?>; Показано: <?= $pagination->totalItemCount ?></div>

        <?php PaginationView::Render($pagination) ?>

        <style>
            table.policyTable {
                width: 70%;
            }

            table.policyTable tbody tr:nth-of-type(odd) {
                background-color: rgba(0, 0, 0, .05);
            }

            table.policyTable thead tr th {
                padding: 15px 0;
            }

            table.policyTable tbody tr td {
                text-align: center;
                padding: 5px 0;
            }

            a.edit {
                margin-right: 40px;
            }

            table.policyTable tbody trtable__pageNomber {
                background: none;
            }

            table.policyTable tbody table__pageNomber table__pageLink {
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

            table.policyTable tbody table__pageNomber table__pageLink.active,
            table.policyTable table tbody table__pageNomber table__pageLink:hover {
                background: #0073aa;
                color: #fff;
            }
        </style>

<?php
    }
}
