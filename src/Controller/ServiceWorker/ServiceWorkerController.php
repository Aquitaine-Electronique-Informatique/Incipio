<?php

namespace App\Controller\ServiceWorker;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class ServiceWorkerController extends Controller
{
    /**
     * @Route(path="/sw.js", name="sw", methods={"GET"})
     */
    public function serveServiceWorker(Request $request) 
    {
        $sw = '../../../public/js/sw.js';
        $response = new BinaryFileResponse($file);
        $response->headers->set('Content-Type', 'application/javascript');
        return $response;
    }
    
}
