<?php

namespace C4lPartners\Views\Partner;

use C4lPartners\Views\BaseView;

class PartnerEditView extends BaseView
{
    public function Render($partner, $isUpdated)
    {
?>
        <h1>Редактировать компанию партнера "<?= $partner->name ?>"</h1>

        <p><a href="<?= $this->admin_root_url() ?>">Назад к списку</a></p>

        <?php if ($isUpdated) : ?>
            <p style="color:red;">Данные обновлены</p>
        <?php endif; ?>

        <div>
            <nav class="health-check-tabs-wrapper hide-if-no-js" aria-label="Secondary menu">
                <a href="<?= $this->add_query_arg("edit_partner_by_id=$partner->id&tab=general_tab", '') ?>" class="health-check-tab active">
                    Настройки </a>

                <a href="<?= $this->add_query_arg("edit_partner_by_id=$partner->id&tab=client_tab", '') ?>" class="health-check-tab" aria-current="true">
                    Клиенты </a>
            </nav>
        </div>

        <div>
            <form method="POST">
                <h2>Обшие настройки</h2>
                <div style="max-width: 250px;">
                    <input type="hidden" name="partner[id]" value="<?= $partner->id ?>">

                    <div class="form-group">
                        <label for="partner[name]">Название компании</label>
                        <input class="form-control" type="text" placeholder="Название компании" name="partner[name]" required value="<?= $partner->name ?>" />
                    </div>
                    <div class="form-group">
                        <label for="partner[link]">Ссылки для страницы входа</label>
                        <input class="form-control" type="text" placeholder="Ссылка" name="partner[link]" required value="<?= $partner->link ?>" />
                    </div>
                </div>

                <h2>Настройки страницы входа</h2>
                <div style="max-width: 300px;">
                    <div class="form-group">
                        <label for="partner[description]">Текст приветствия</label>
                        <textarea class="form-control" rows="3" name="partner[description]" required><?= $partner->description ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="partner[logoLink]">Ссылка на логотип</label>
                        <input class="form-control" type="text" placeholder="Название компании" name="partner[logoLink]" required value="<?= $partner->logoLink ?>" />
                    </div>
                    <div class="form-group">
                        <label for="partner[loginNameInput]">Название логина (<small>для поля на форме</small>)</label>
                        <input class="form-control" type="text" placeholder="Название логина" name="partner[loginNameInput]" required value="<?= $partner->loginNameInput ?>" />
                    </div>
                    <div class="form-group">
                        <label for="partner[passwordNameInput]">Название пароля (<small>для поля на форме</small>)</label>
                        <input class="form-control" type="text" placeholder="Название пароля" name="partner[passwordNameInput]" required value="<?= $partner->passwordNameInput ?>" />
                    </div>
                    <div class="form-group">
                        <label for="partner[inputType]">Тип пароля (<small>для поля на форме</small>)</label>

                        <?php $options = array('', 'text', 'password', 'date', 'number'); ?>

                        <select class="form-control" placeholder="Название пароля" name="partner[inputType]" required>
                            <?php foreach ($options as $value) : ?>
                                <option <?= $value == $partner->inputType ? 'selected' : '' ?> value="<?= $value ?>"><?= $value ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>

                <div class="form-group">
                    <button class="button button-primary">Сохранить</button>
                </div>
            </form>
        </div>

        <style>
            .form-group {
                margin-bottom: 1rem;
            }

            label {
                display: inline-block;
                margin-bottom: .5rem;
            }

            .form-control {
                display: block;
                width: 100%;
                /* height: calc(2.25rem + 2px); */
                padding: .375rem .75rem;
                /* font-size: 1rem; */
                line-height: 1.5;
                color: #495057;
                background-color: #fff;
                background-clip: padding-box;
                /* border: 1px solid #ced4da; */
                border-radius: .25rem;
                transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            }

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
        </style>

<?php
    }
}
