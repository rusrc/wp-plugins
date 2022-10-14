<?php

namespace C4lPartners\Views\Partner;

use C4lPartners\Views\BaseView;

class PartnerCsvView extends BaseView
{
    public function Render($isUploadedSuccessfully)
    {
?>
        <h1>Партнёры</h1>

        <?php if ($isUploadedSuccessfully) : ?>

            <h3>Файл загружен</h3>

        <?php else : ?>

            <h3>Загрузить CSV</h3>
            <form enctype="multipart/form-data" method="POST">
                <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                <input name="csv-file" type="file" />
                <input class="button button-primary" type="submit" value="Загрузить CSV файл в базу" />
            </form>

        <?php endif; ?>

        <br>
        <a href="<?= $this->admin_root_url() ?>">Назад к списку</a>
<?php
    }
}
