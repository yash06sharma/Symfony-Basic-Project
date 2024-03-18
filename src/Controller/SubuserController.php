<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request; 
use Doctrine\Persistence\ManagerRegistry;


#[Route('/api', name: 'api_')]
class SubuserController extends AbstractController
{
    /**private $em */
    private $em;

    /**
     * Responsible for initialization
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine,) {
        $this->em = $doctrine->getManager();
    }
    #[Route('/subuser', name: 'app_subuser')]
    public function index(): Response
    {
        return $this->render('subuser/index.html.twig', [
            'controller_name' => 'SubuserController',
        ]);
    }

    /**
     * Responsible to create User
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/customevent', name: 'app_user_add')]
    public function createUser(Request $request): JsonResponse
    {   
        try {
            dd("Custom Event Listner");

            return $this->json([
                'message' => 'Registered Successfully',
            ]);
        } catch (\Throwable $th) {
            return $this->json(['message' => $th]);
        }
    }


}
