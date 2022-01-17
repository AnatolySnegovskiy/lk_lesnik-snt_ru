<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Form\Model\ChangePassword;
use App\Form\UserInfoType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(Request $request, ManagerRegistry $registry, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = (new UserRepository($registry))
            ->find($this->getUser()->getId());

        $formPassword = $this->createForm(ChangePasswordType::class, $user);
        $form = $this->createForm(UserInfoType::class, $user->getUserInfo());
        $form->handleRequest($request);
        $formPassword->handleRequest($request);

        if (($form->isSubmitted() && $form->isValid()) || ($formPassword->isSubmitted() && $formPassword->isValid())) {
            if ($formPassword->isSubmitted()) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $user->getPassword()
                    )
                );
            }

            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'form' => $form->createView(),
            'formPassword' => $formPassword->createView()
        ]);
    }
}
