<?php

namespace App\Controller;

use App\Mapper\PatientMapper;
use FOS\RestBundle\View\View;
use App\Service\PatientService;
use App\Service\PatientServiceException;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;


class PatientRestController extends AbstractFOSRestController
{

    private $patientService;
    private $entityManager;
    private $patientMapper;

    const URI_PATIENT_COLLECTION = "/patients";
    const URI_PATIENT_INSTANCE = "/patients/{id}";
    
    public function __construct(PatientService $patientService, 
                                EntityManagerInterface $entityManager,
                                PatientMapper $mapper){
        $this->patientService =$patientService;
        $this->entityManager = $entityManager;
        $this->patientMapper = $mapper;
    }
    
    /**
     * @Get(PatientRestController::URI_PATIENT_COLLECTION)
     */
    public function searchAll()
    {
        try {
            $patients = $this->patientService->searchAll();
        } catch(PatientServiceException $e){
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
        if($patients){
            return View::create($patients, Response::HTTP_OK, ["Content-type" => "application/json"]);
        } else {
            return View::create($patients, Response::HTTP_NOT_FOUND, ["Content-type" => "application/json"]);
        }
    }

}