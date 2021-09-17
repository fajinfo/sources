<?php

namespace App\Controller;


use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AutoDeployController extends AbstractController
{
    /**
     * @param Request $request
     * @param LoggerInterface $logger
     * @return Response
     *
     * @Route("/api/auto_deploy", name="api_auto_deploy" )
     */
    public function autoDeploy(Request $request, LoggerInterface $logger){
        $api_key = $request->headers->get('Authorization');
        if($api_key != $this->getParameter('app.autoDeploy_api_key')){
            return new Response('Autorization not valid', Response::HTTP_FORBIDDEN);
        }
        $commands = array(
            'git pull',
            'git status',
            'git submodule sync',
            'git submodule update',
            'git submodule status',
            'composer update',
            'php bin/console cache:clear'
        );
        $logger->info('-- Starting AutoDeployer Script --');
        foreach($commands as $command){
            try {
                $tmp = \shell_exec($command);
                $logger->info('Command executed : '.$command, ['return' => $tmp] );
            }catch (\Exception $e){
                $logger->alert('Command Failed : '.$command, ['Error' => $e->getMessage()]);
            }
        }
        $logger->info('-- Ending AutoDeployer Script --');
        return new Response('AutoDeployer success !', Response::HTTP_OK);
    }
}
