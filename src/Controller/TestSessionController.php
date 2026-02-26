<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestSessionController extends AbstractController
{
    #[Route('/test-session', name: 'test_session')]
    public function test(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('ping', 'pong');

        return new Response('session id = '.$session->getId().' | ping = '.$session->get('ping'));
    }
}