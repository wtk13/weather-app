<?php
declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\DTO\LatLngDTO;
use AppBundle\Form\LatLngType;
use AppBundle\Processor\LocationProcessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LocationController extends BaseController
{
    public function addAction(Request $request, LocationProcessor $locationProcessor): Response
    {
        $latLngDTO = new LatLngDTO();

        $form = $this->createForm(LatLngType::class, $latLngDTO);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        return $this->createApiResponse($locationProcessor->process($latLngDTO), 201);
    }
}
