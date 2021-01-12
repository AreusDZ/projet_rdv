<?php

namespace App\Controller;


use App\Entity\RendezVous;
use App\Entity\RendezVousDTO;
use FOS\RestBundle\View\View;
use App\Mapper\RendezVousMapper;
use App\Service\RendezVousService;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\RendezVousServiceException;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class RendezVousRestController extends AbstractFOSRestController
{

    private $rendezVousService;
    private $entityManager;
    private $rendezVousMapper;

    const URI_RENDEZVOUS_COLLECTION = "/rendezVouss";
    const URI_RENDEZVOUS_INSTANCE = "/rendezVous/{id}";
    const URI_RENDEZVOUSPATIENT_INSTANCE = "/rendezVouss/patient/{idPatient}";
    const URI_RENDEZVOUSPRATICIEN_INSTANCE = "/rendezVouss/praticien/{idPraticien}";
    
    public function __construct(RendezVousService $rendezVousService, 
                                EntityManagerInterface $entityManager,
                                RendezVousMapper $mapper){
        $this->rendezVousService =$rendezVousService;
        $this->entityManager = $entityManager;
        $this->rendezVousMapper = $mapper;
    }
    
    /**
     * @Get(RendezVousRestController::URI_RENDEZVOUS_COLLECTION)
     */
    public function searchAll()
    {
        try {
            $rendezVouss = $this->rendezVousService->searchAll();
        } catch(RendezVousServiceException $e){
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
        if($rendezVouss){
            return View::create($rendezVouss, Response::HTTP_OK, ["Content-type" => "application/json"]);
        } else {
            return View::create($rendezVouss, Response::HTTP_NOT_FOUND, ["Content-type" => "application/json"]);
        }
    }
    
    /**
     * @Delete(RendezVousRestController::URI_RENDEZVOUS_INSTANCE)
     *
     * @param [type] $id
     * @return void
     */
    public function remove(RendezVous $rendezVouss){
        try {
            $this->rendezVousService->delete($rendezVouss);
            return View::create([], Response::HTTP_NO_CONTENT, ["Content-type" => "application/json"]);
        } catch(RendezVousServiceException $e){
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
    }
     /**
     * @Get(RendezVousRestController::URI_RENDEZVOUS_INSTANCE)
     *
     * @return void
     */
    public function searchById(int $id){
        try {
            $rendezVousDto = $this->rendezVousService->searchById($id);
        }catch (RendezVousServiceException $e){
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
        if($rendezVousDto){
            return View::create($rendezVousDto, Response::HTTP_OK, ["Content-type" => "application/json"]);
        } else {
            return View::create([], Response::HTTP_NOT_FOUND, ["Content-type" => "application/json"]);
        }
    }

    /**
     * @Get(RendezVousRestController::URI_RENDEZVOUSPATIENT_INSTANCE)
     *
     * @return void
     */
    public function searchRdvByIdPatient(int $idPatient) {
        try {
            $rendezVousDto = $this->rendezVousService->searchByIdPatient($idPatient);
        }catch (RendezVousServiceException $e){
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }

        if($rendezVousDto){
            return View::create($rendezVousDto, Response::HTTP_OK, ["Content-type" => "application/json"]);
        } else {
            return View::create([], Response::HTTP_NOT_FOUND, ["Content-type" => "application/json"]);
        }
    }

    /**
     * @Get(RendezVousRestController::URI_RENDEZVOUSPRATICIEN_INSTANCE)
     *
     * @return void
     */
    public function searchRdvByIdPraticien(int $idPraticien){
        try {
            $rendezVousDto = $this->rendezVousService->searchByIdPraticien($idPraticien);
        }catch (RendezVousServiceException $e){
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }

        if($rendezVousDto){
            return View::create($rendezVousDto, Response::HTTP_OK, ["Content-type" => "application/json"]);
        } else {
            return View::create([], Response::HTTP_NOT_FOUND, ["Content-type" => "application/json"]);
        }
    }

    /**
     * @Post(RendezVousRestController::URI_RENDEZVOUS_COLLECTION)
     * @ParamConverter("rendezVousDTO", converter="fos_rest.request_body")
     * @return void
     */
    public function create(RendezVousDTO $rendezVousDTO) {
        try {
            $rendezVous = new RendezVous();
            $this->rendezVousService->persist($rendezVous, $rendezVousDTO);
            return View::create([], Response::HTTP_CREATED, ["Content-type" => "application/json"]);
        } catch (RendezVousServiceException $e) {
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
    }

}
