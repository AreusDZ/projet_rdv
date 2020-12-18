<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\PatientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Controller\UserAuthentificatorAuthenticator;


class PatientController extends AbstractController
{
    /**
     * @Route("/patient", name="patient")
     */
    public function index(): Response
    {
        return $this->render('patient/index.html.twig', [
            'controller_name' => 'PatientController',
        ]);
    }
    /**
     * @Route("/AddPatient", name="AddPatient")
     */
    public function addPatient(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthentificatorAuthenticator $authenticator) :Response {
        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $patient->setPassword(
                    $passwordEncoder->encodePassword(
                        $patient,
                        $form->get('password')->getData()
                    )
                );

                $manager->persist($patient);
                $manager->flush(); 

                return $guardHandler->authenticateUserAndHandleSuccess(
                    $patient,
                    $request,
                    $authenticator,
                    'main'
                );

            } catch (ServiceException $e) {
                return $this->render('main/index.html.twig', [
                    'error' => $e->getMessage(),
                ]);
            } 
            return $this->redirectToRoute('main');
        }

        return $this->render('registration/AddNewPatient.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
