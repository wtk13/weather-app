<?php
declare(strict_types=1);

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    public function historyAction()
    {
        return $this->render('default/history.html.twig');
    }
}
