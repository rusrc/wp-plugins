<?php

namespace C4lPartners\Views;

use C4lPartners\Models\Pagination;
use C4lPartners\Models\Partner;

class PartnerLoginView
{
    static function Render(Partner $partner)
    {
?>
        <div class="container all-text-content">
            <div class="row">
                <div class="col-md-6 col-lg-7 desctopKapLife">

                    <h4 style=" text-align: center; ">Здравствуйте, уважаемые клиенты!</h4>

                    <?php echo $partner->description ?>

                </div>
                <div class="col-sm-12 col-md-6 col-lg-5 formKapLife">
                    <section id="capitallife_policyform-2" class="widget widget_capitallife_policyform amr_widget">
                        <form class="klife-box" method="GET">

                            <input type="hidden" name="partner[Id]" value="<?= $partner->id?>">

                            <div class="klife-box-img">
                                <img src="<?= $partner->logoLink ?>" alt="<?= $partner->name ?>">
                            </div>

                            <div class="form-group field-policy-number">
                                <input type="text" 
                                id="policy-number" 
                                class="form-control" 
                                name="partner[loginNameInput]" 
                                placeholder="<?= $partner->loginNameInput?>" autocomplete="off" aria-required="true" aria-invalid="true">
                            </div>

                            <div class="form-group field-date-birth">
                                <input id="date-birth" class="form-control" name="partner[passwordNameInput]" type="<?= $partner->inputType ?? 'password' ?>">
                            </div>

                            <div class="form-group">
                                <button type="submit" id="start-button" class="btn btn-red">Получить услугу</button>
                            </div>

                        </form>
                    </section>
                </div>
                <div class="col-sm-12 mobileKapLife">

                    <h4 style=" text-align: center; ">Здравствуйте, уважаемые клиенты!</h4>

                    <?php echo $partner->description ?>

                </div>
            </div>
        </div>

        <style>
        
        </style>

<?php
    }
}
