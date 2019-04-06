<?php
declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\DTO\LatLngDTO;
use AppBundle\Form\LatLngType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LocationController extends BaseController
{
    public function addAction(Request $request): Response
    {
        $latLngDTO = new LatLngDTO();

        $form = $this->createForm(LatLngType::class, $latLngDTO);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        return $this->createApiResponse($latLngDTO);
    }
}
