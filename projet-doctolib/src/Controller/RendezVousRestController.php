<?php

namespace App\Controller;


use App\Entity\RendezVous;
use App\Entity\RendezVousDTO;
use FOS\RestBundle\View\View;
use App\Mapper\RendezVousMapper;
use OpenApi\Annotations as OA;
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

/**
 * @OA\Info(
 *      title="RendezVous Management",
 *      description="RendezVous manager (GET,PUT,DELETE,POST)",
 *      version="0.01",
 * )
 */
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
      *@OA\Get(
      *     path="/rendezVouss",
      *     tags={"RendezVous"},
      *     summary="Returns a list of RendezVousDTO",
      *     description="Returns a list of RendezVousDTO",
      *     @OA\Response(
      *         response=200,
      *         description="Successful operation", 
      *         @OA\JsonContent(ref="#/components/schemas/RendezVousDTO")   
      *     ),
      *      @OA\Response(
      *         response=404,
      *         description="If no RendezVousDTO found",    
      *     ),
      *      @OA\Response(
      *         response=500,
      *         description="Internal server Error. Please contact us",    
      *     )
      * )
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
     *@OA\Delete(
     *     path="/rendezVous/{id}",
     *     tags={"RendezVous"},
     *     description="Delete an object of type RendezVous",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Successfully deleted"
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server Error. Please contact us",    
     *     )
     * )
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
     * @OA\Get(
     *   path="/rendezVous/{id}",
     *   tags={"RendezVous"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="number")
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="The RDV",
     *     @OA\JsonContent(ref="#/components/schemas/RendezVousDTO")
     *   ),
     *   @OA\Response(
     *     response="500",
     *     description="Internal server Error. Please contact us",
     *   ),
     *   @OA\Response(
     *     response="404",
     *     description="No RDV found for this id",
     *   )
     * )
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
     * @OA\Get(
     *   path="/rendezVous/patient/{id}",
     *   tags={"RendezVous"},
     *   summary="Return a list of RendezVousDTO from a patient id",
     *   description="Return information about a RendezVousDTO",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="number")
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Rdvs from a patient id",
     *     @OA\JsonContent(ref="#/components/schemas/RendezVousDTO")
     *   ),
     *   @OA\Response(
     *     response="500",
     *     description="Internal server Error. Please contact us",
     *   ),
     *   @OA\Response(
     *     response="404",
     *     description="No user found for this id",
     *   )
     * )
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
     * @OA\Get(
     *   path="/rendezVous/praticien/{id}",
     *   tags={"RendezVous"},
     *   summary="Return a list of RendezVousDTO from a praticien id",
     *   description="Return information about a RendezVousDTO",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="number")
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="RendezVous from a praticien id",
     *     @OA\JsonContent(ref="#/components/schemas/RendezVousDTO")
     *   ),
     *   @OA\Response(
     *     response="500",
     *     description="Internal server Error. Please contact us",
     *   ),
     *   @OA\Response(
     *     response="404",
     *     description="No user found for this id",
     *   )
     * )
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
     * @OA\Post(
     *     path="/rendezVouss",
     *     tags={"RendezVous"},
     *     summary="Add a new RendezVousDTO",
     *     description="Create a object of type RendezVousDTO",
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="date",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="adresse",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="patient",
     *                      type="number"
     *                  ),
     *                  @OA\Property(
     *                      property="praticien",
     *                      type="number"
     *                  ),
     *                  example={"date": "2021-01-12T10:46:59+01:00", "adresse": "13 Boulevard de l'exemple", "patient": 0, "praticien": 0}
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid request body"
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successfully created", 
     *         @OA\JsonContent(ref="#/components/schemas/PraticienDTO")   
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server Error. Please contact us",    
     *     )
     * )
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
