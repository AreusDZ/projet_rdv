<?php

namespace App\Controller;

use App\Entity\Praticien;
use App\Form\PraticienType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function addPraticien(EntityManagerInterface $manager, Request $request) :Response {
        $praticien = new Praticien();
        $form = $this->createForm(PraticienType::class, $praticien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
                $manager->persist($praticien);
                $manager->flush(); 
           
            return $this->redirectToRoute('main');
        }

        return $this->render('praticien/AddPraticien.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
