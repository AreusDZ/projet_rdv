<?php

namespace App\Controller;

use App\Entity\Praticien;
use App\Form\PraticienType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\DriverException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PraticienController extends AbstractController
{
    /**
     * @Route("/praticien", name="praticien")
     */
    public function index(): Response
    {
        return $this->render('praticien/index.html.twig', [
            'controller_name' => 'PraticienController',
        ]);
    }
      /**
     * @Route("/AddPraticien", name="AddPraticien")
     */
    public function addPraticien(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppCustomAuthenticator $authenticator) :Response {
        $praticien = new Praticien();
        $form = $this->createForm(PraticienType::class, $praticien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $praticien->setPassword(
                    $passwordEncoder->encodePassword(
                        $praticien,
                        $form->get('password')->getData()
                    )
                );

                $manager->persist($praticien);
                $manager->flush(); 

                return $guardHandler->authenticateUserAndHandleSuccess(
                    $praticien,
                    $request,
                    $authenticator,
                    'main'
                );
            } catch (DriverException $e) {
                return $this->render('main/index.html.twig', [
                    'error' => $e->getMessage(),
                ]);
            } 
            return $this->redirectToRoute('main');
        }

        return $this->render('praticien/AddPraticien.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
