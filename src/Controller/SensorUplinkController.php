<?php

namespace App\Controller;

use App\Entity\Sensors;
use App\Entity\SensorsUplinks;
use App\Repository\HourlyFlowRepository;
use App\Repository\SensorsRepository;
use App\Repository\SensorsUplinksRepository;
use App\Repository\SourcesRepository;
use Doctrine\ORM\EntityManagerInterface;
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

                $sensor->setLastSeen($uplink->getDate())
                    ->setLastBattery($uplink->getBattery());

                $em = $this->getDoctrine()->getManager();
                $em->persist($uplink);
                $em->persist($sensor);
                $em->flush();

                $logger->info('Tracker information received');
                return new JsonResponse([], Response::HTTP_OK);

            }
            $logger->error('Tracker information received without the EUI Registred ', ['EUI_Received' => bin2hex(base64_decode($data['devEUI']))]);
            return new JsonResponse(['error' => 'Tracker not registered'], Response::HTTP_NOT_FOUND);
        }
        $logger->error('Tracker information received without the correct Autorization Header ', ['Authorization' => $request->headers->get('Authorization')]);
        return new JsonResponse(['error' => 'Not Valid Authorization Key'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param SourcesRepository $sourcesRepository
     * @param SensorsUplinksRepository $sensorsUplinksRepository
     * @param EntityManagerInterface $em
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route("api/archive/uplink", name="api_archive_uplink")
     */
    public function archive(SourcesRepository $sourcesRepository, SensorsUplinksRepository $sensorsUplinksRepository, HourlyFlowRepository $hourlyFlowRepository, EntityManagerInterface $em){
        $sources = $sourcesRepository->findAll();

        /**
         * On Archive les données du même jour il y a un mois dans la base "HourlyFlow"
         */
        foreach($sources as $source){
            $working_date = new \DateTime();
            $working_date->setTime('0', '0', '0');
            $working_date->modify('-1 month');
            $hourlyFlow = array();
            for($i=0; $i <24; $i++){
                $hourlyFlow[$i] = $sensorsUplinksRepository->getForArchive($working_date, $source);
                if($hourlyFlow[$i] != null){
                    $em->persist($hourlyFlow[$i]);
                }
                $working_date->modify('+1 hour');
            }
            $em->flush();
        }
        $working_date->modify('-24 hour');
        $sensorsUplinksRepository->removeArchivedDay($working_date);
        $em->flush();

        /**
         * Si nous sommes le premier jour du mois, on archive le même mois de l'an dernier dans la base "DailyFlow"
         */
        if($working_date->format('d') == 1) {

            // TODO: Add System to work from 28 to 31 day with the code above.
            foreach ($sources as $source) {
                $working_date = new \DateTime();
                $working_date->modify('-1 year');
                $dailyFlow = array();
                for ($i = 0; $i < $working_date->format('t'); $i++) {
                    $dailyFlow[$i] = $hourlyFlowRepository->getForArchive($working_date, $source);
                    if ($dailyFlow[$i] != null) {
                        $em->persist($dailyFlow[$i]);
                    }
                    $working_date->modify('+1 day');
                }
                $em->flush();
            }
            $working_date->modify('first day of last month');
            $hourlyFlowRepository->removeArchivedMonth($working_date);
            $em->flush();
        }


        return new Response('Success');
    }
}
