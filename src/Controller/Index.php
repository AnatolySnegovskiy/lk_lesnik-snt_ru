<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Index extends AbstractController
{
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('login', [], 301);
    }
}