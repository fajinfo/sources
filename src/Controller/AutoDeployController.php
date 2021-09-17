<?php

namespace App\Controller;


use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
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
    public function autoDeploy(Request $request, LoggerInterface $logger, KernelInterface $kernel){
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

        $application = new Application($kernel);
        $application->setAutoExit(false);

        foreach($commands as $command){
            try {
                $input = new ArrayInput([
                    'command' => $command
                ]);
                $output = new BufferedOutput();
                $application->run($input, $output);
                $logger->info('Command executed : '.$command, ['return' => $output->fetch()] );
            }catch (\Exception $e){
                $logger->alert('Command Failed : '.$command, ['Error' => $e->getMessage()]);
            }
        }
        $logger->info('-- Ending AutoDeployer Script --');
        return new Response('AutoDeployer success !', Response::HTTP_OK);
    }
}
