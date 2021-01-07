<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\PatientType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\DriverException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



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
    public function addPatient(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppCustomAuthenticator $authenticator) :Response {
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

            } catch (DriverException $e) {
                return $this->render('main/index.html.twig', [
                    'error' => $e->getMessage(),
                ]);
            } 
            return $this->redirectToRoute('main');
        }

        return $this->render('patient/AddPatient.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
