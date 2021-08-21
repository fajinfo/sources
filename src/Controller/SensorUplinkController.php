<?php

namespace App\Controller;

use App\Entity\Sensors;
use App\Entity\SensorsUplinks;
use App\Repository\SensorsRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SensorUplinkController extends AbstractController
{
    /**
     * @Route("/api/sensor/uplink", name="sensor_uplink")
     */
    public function index(Request $request, SensorsRepository $repository, LoggerInterface $logger): Response
    {
        if($request->headers->get('Authorization') === 'oHAwmnQLI89B8WgPq4tC8MyQbKEOD1fR'){
            $data = json_decode($request->getContent(), true);
            $sensor = $repository->findOneBy(['devEui' => bin2hex(base64_decode($data['devEUI']))]);

            if($sensor instanceof Sensors){
                $uplink = new SensorsUplinks();
                $uplink->setDate(new \DateTime())
                    ->setSensor($sensor);

                $payload = json_decode(stripslashes($data['objectJSON']), true);

                if(!empty($json['Bat'])){
                    $uplink->setBattery($json['Bat']);
                }

                if(!empty($json['FlowRate'])){
                    $uplink->setWaterFlowRate('FlowRate');
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($uplink);
                $em->flush();

                return new JsonResponse([], Response::HTTP_OK);

            }
            $logger->error('Tracker information received without the EUI Registred ', ['EUI_Received' => bin2hex(base64_decode($data['devEUI']))]);
            return new JsonResponse(['error' => 'Tracker not registered'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse(['error' => 'Not Valid Authorization Key'], Response::HTTP_UNAUTHORIZED);
    }
}
