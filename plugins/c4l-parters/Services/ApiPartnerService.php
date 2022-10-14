<?php

namespace C4lPartners\Services;

use C4lPartners\Models\Partner;
use C4lPartners\Repositories\PartnerRepository;

class ApiPartnerService
{
    private PartnerRepository $_partnerRepository;

    public function __construct(PartnerRepository $partnerRepository)
    {
        $this->_partnerRepository = $partnerRepository;
    }

    public function Init()
    {
        // POST c4l-api/v1/partner
        register_rest_route(
            'c4l-api/v1',
            '/partner',
            array(
                'methods'  => 'POST',
                'callback' => array($this, 'Create'),
                'permission_callback' => function ($request) {
                    // TODO add permission
                    return false;
                },
                'args' => array(
                    'name' => array(
                        'default'           => null,
                        'required'          => false
                    ),
                    'link' => array(
                        'default'           => null,
                        'required'          => false
                    )
                )
            )
        );
    }

    function Create($request)
    {
        $partner = new Partner($request->get_params());

        $this->_partnerRepository->Save($partner);

        $response = rest_ensure_response($partner);
        $response->set_status(201);

        return $response;
    }
}
