<?php
declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\DTO\LatLngDTO;
use AppBundle\Form\LatLngType;
use AppBundle\Manager\WeatherManager;
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

    public function listAction(Request $request, WeatherManager $weatherManager): Response
    {
        $page = $request->query->getInt('page', 1);

        return $this->createApiResponse($weatherManager->list($page));
    }

    public function summaryAction(WeatherManager $weatherManager): Response
    {
        return $this->createApiResponse($weatherManager->summary());
    }
}
