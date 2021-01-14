<?php

namespace App\Controller;


use App\Entity\Praticien;
use App\Entity\PraticienDTO;
use FOS\RestBundle\View\View;
use App\Mapper\PraticienMapper;
use OpenApi\Annotations as OA;
use App\Service\PraticienService;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\PraticienServiceException;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @OA\Info(
 *      title="Praticien Management",
 *      description="Praticien manager (GET,PUT,DELETE,POST)",
 *      version="0.01",
 * )
 */
class PraticienRestController extends AbstractFOSRestController
{

    private $praticienService;
    private $entityManager;
    private $praticienMapper;

    const URI_PRATICIEN_COLLECTION = "/praticiens";
    const URI_PRATICIEN_INSTANCE = "/praticien/{id}";
    
    public function __construct(PraticienService $praticienService, 
                                EntityManagerInterface $entityManager,
                                PraticienMapper $mapper){
        $this->praticienService =$praticienService;
        $this->entityManager = $entityManager;
        $this->praticienMapper = $mapper;
    }
    
    /**
     *@OA\Get(
     *     path="/praticiens",
     *     tags={"Praticien"},
     *     summary="Returns a list of PraticienDTO",
     *     description="Returns a list of PraticienDTO",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation", 
     *         @OA\JsonContent(ref="#/components/schemas/PraticienDTO")   
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="If no PraticienDTO found",    
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server Error. Please contact us",    
     *     )
     * )
     * @Get(PraticienRestController::URI_PRATICIEN_COLLECTION)
     */
    public function searchAll()
    {
        try {
            $praticiens = $this->praticienService->searchAll();
        } catch(PraticienServiceException $e){
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
        if($praticiens){
            return View::create($praticiens, Response::HTTP_OK, ["Content-type" => "application/json"]);
        } else {
            return View::create($praticiens, Response::HTTP_NOT_FOUND, ["Content-type" => "application/json"]);
        }
    }
    
    /** 
     *@OA\Delete(
     *     path="/praticien/{id}",
     *     tags={"Praticien"},
     *     description="Delete an object of type Praticien",
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
     * @Delete(PraticienRestController::URI_PRATICIEN_INSTANCE)
     *
     * @param [type] $id
     * @return void
     */
    public function remove(Praticien $praticiens){
        try {
            $this->praticienService->delete($praticiens);
            return View::create([], Response::HTTP_NO_CONTENT, ["Content-type" => "application/json"]);
        } catch(PraticienServiceException $e){
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
    }
      /** 
     * @OA\Get(
     *   path="/praticien/{id}",
     *   tags={"Praticien"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="number")
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="The User",
     *     @OA\JsonContent(ref="#/components/schemas/PraticienDTO")
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
     * @Get(PraticienRestController::URI_PRATICIEN_INSTANCE)
     *
     * @return void
     */
    public function searchById(int $id){
        try {
            $praticienDto = $this->praticienService->searchById($id);
        }catch (PraticienServiceException $e){
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
        if($praticienDto){
            return View::create($praticienDto, Response::HTTP_OK, ["Content-type" => "application/json"]);
        } else {
            return View::create([], Response::HTTP_NOT_FOUND, ["Content-type" => "application/json"]);
        }
    }

    /**
     *@OA\Post(
     *     path="/praticiens",
     *     tags={"Praticien"},
     *     summary="Add a new PraticienDTO",
     *     description="Create an object of type PraticienDTO",
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="nom",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="specialite",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string"
     *                  ),
     *                  example={"email": "exemple@gmail.com", "nom": "nomExemple", "specialite": "specialiteExemple", "password": "pwdExemple"}
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
     * @Post(PraticienRestController::URI_PRATICIEN_COLLECTION)
     * @ParamConverter("praticienDTO", converter="fos_rest.request_body")
     * @return void
     */
    public function create(PraticienDTO $praticienDTO) {
        try {
            $praticien = new Praticien();
            $this->praticienService->persist($praticien, $praticienDTO);
            return View::create([], Response::HTTP_CREATED, ["Content-type" => "application/json"]);
        } catch (PraticienServiceException $e) {
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
    }
    
   /**
     *@OA\Put(
     *     path="/praticien/{id}",
     *     tags={"Praticien"},
     *     description="Modify an object of type PraticienDTO",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *               @OA\Schema(
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="nom",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="specialite",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string"
     *                  ),
     *                  example={"email": "exemple@gmail.com", "nom": "nomExemple", "specialite": "specialiteExemple", "password": "pwdExemple"}
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully modified", 
     *         @OA\JsonContent(ref="#/components/schemas/PraticienDTO")   
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server Error. Please contact us",    
     *     )
     * )  
     * @Put(PraticienRestController::URI_PRATICIEN_INSTANCE)
     * @ParamConverter("praticienDTO", converter="fos_rest.request_body")
     * @param PraticienDTO $praticienDTO
     * @return void
     */
    public function update(Praticien $praticien, PraticienDTO $praticienDTO) {
        try {
            $this->praticienService->persist($praticien, $praticienDTO);
            return View::create([], Response::HTTP_OK, ["Content-type" => "application/json"]);
        } catch (PraticienServiceException $e) {
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
    }
}
