<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class UserListController extends AbstractController
{
    /**
     * @Route("/users/list", name="user_list")
     */
    public function index(ManagerRegistry $registry): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $users = (new UserRepository($registry))->findAll();

        return $this->render('user_list/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/users/add", name="app_add_user")
     */
    public function add(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, EmailVerifier $emailVerifier): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $user->setIsVerified(true);
        $password = $this->randomPassword();

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $password
                )
            );

            $emailVerifier->sendEmailConfirmation($user,
                (new TemplatedEmail())
                    ->from(new Address($emailVerifier::FROM_EMAIL, 'BOt'))
                    ->to($user->getUserInfo()->getEmail())
                    ->subject('You password')
                    ->htmlTemplate('email/confirmation_email.html.twig'),
                $password
            );

            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('user_list/useradd.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/users/remove/{id}", name="app_remove_user")
     */
    public function remove(int $id, ManagerRegistry $registry): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $repo = (new UserRepository($registry));
        $registry->getManager()->remove($repo->find($id));
        $registry->getManager()->flush();
        return $this->redirectToRoute('user_list');
    }

    /**
     * @Route("/users/resetpassword/{id}", name="app_reset_password_user")
     */
    public function resetPassword(int $id, ManagerRegistry $registry, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, EmailVerifier $emailVerifier): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = (new UserRepository($registry))
            ->find($id);
        $password = $this->randomPassword();
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );

        $emailVerifier->sendEmailConfirmation($user,
            (new TemplatedEmail())
                ->from(new Address($emailVerifier::FROM_EMAIL, 'BOt'))
                ->to($user->getUserInfo()->getEmail())
                ->subject('You new Password')
                ->htmlTemplate('email/confirmation_email.html.twig'),
            $password
        );

        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('user_list');
    }

    private function randomPassword(): string
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;

        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass);
    }
}
