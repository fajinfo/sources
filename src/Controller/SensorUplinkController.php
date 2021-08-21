<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SensorUplinkController extends AbstractController
{
    /**
     * @Route("/api/sensor/uplink", name="sensor_uplink")
     */
    public function index(): Response
    {
	
        return $this->render('sensor_uplink/index.html.twig', [
            'controller_name' => 'SensorUplinkController',
        ]);
    }
}
