<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Login extends AbstractController
{
    public function index(): Response
    {
        return $this->render('/authorization/login.html.twig');
    }

    public function enter(ManagerRegistry $registry, Request $request)
    {
        $data = (new UserRepository($registry))->findOneBy(['personal_account' => $request->get('login', 'TEST')]);
        var_dump($data->getEmail());
    }
}