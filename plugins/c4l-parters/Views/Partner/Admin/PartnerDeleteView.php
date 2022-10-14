<?php

namespace C4lPartners\Views\Partner;

use C4lPartners\Models\Partner;
use C4lPartners\Views\BaseView;

class PartnerDeleteView extends BaseView
{
    public function Render(Partner $partner, bool $isDeleted)
    {
?>
        <h1>Удаление компании</h1>

        <?php if ($isDeleted) : ?>

            <h2 style="color:brown;">Компания '<?= $partner->name ?>' удалена</h2>

        <?php else : ?>

            <h2>Удалить компанию '<?= $partner->name ?>' ?</h2>
            <form method="POST">
                <input type="hidden" name="page" value="<?= $_GET['page'] ?>">
                <input type="hidden" name="partner[name]" value="<?= $partner->name ?>" />
                <table>
                    <tr>
                        <td><button class="button button-primary" type="submit">Да, удалить</button></td>
                    </tr>
                </table>
            </form>

        <?php endif; ?>

        <br>
        <a href="<?= $this->admin_root_url() ?>">Назад к списку</a>
<?php
    }
}
