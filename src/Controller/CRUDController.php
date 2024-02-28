<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Database;
use App\Entity\BasicDetail;
use App\Form\CrudFormType;
use App\Form\BasicDetailFormType;
use App\Repository\BasicDetailRepository;
use App\Repository\DatabaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class CRUDController extends AbstractController
{
    /**
     * $em Global Entity Manager Variable
     */
    private $em;
    /**
     * To initialization
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/", name="app_crud_index", methods: ['GET'])
     * @param DatabaseRepository $databaseRepository
     * @return Response
     */

    #[Route('/', name: 'app_crud_index', methods: ['GET'])]
    public function index(DatabaseRepository $databaseRepository): Response
    {

        return $this->render(
            'CRUD_TEMP/indexCrud.html.twig',
            ['datashow' => $databaseRepository->findAll(),]
        );
    }

    /**
     * @Route("/register", name="app_crud_form", methods: ['GET',['POST']])
     * @param Request $request, SluggerInterface $slugger,  PersistenceManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/register', name: 'app_crud_form', methods: ['GET', 'POST'])]
    public function crudForm(Request $request,  SluggerInterface $slugger,  DatabaseRepository $databaseRepository): Response
    {
        $basicdetail = new BasicDetail();
        $database = new Database();
        $form = $this->createForm(CrudFormType::class, $database, ['attr' => ['id' => 'form1']]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('image')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                $database->setImage($newFilename);
            }

            $basicDetailData = $request->request->all('basic_detail_form');
            $length = count($basicDetailData['state']);
            for ($i = 0; $i < $length; $i++) {
                $basicdetail = new BasicDetail();
                $basicdetail->setState($basicDetailData['state'][$i + 1]); // Adding 1 because array keys are 1-indexed
                $basicdetail->setDist($basicDetailData['dist'][$i + 1]);
                $basicdetail->setZip($basicDetailData['zip'][$i + 1]);
                $basicdetail->setDatabaseUserId($database);
                $this->em->persist($basicdetail);
            }
            $this->em->persist($database);
            try {
                $this->em->flush();
            } catch (\Exception $e) {
                dump($e->getMessage());
                dump($e->getTrace()); 
            }
            return $this->redirectToRoute('app_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('CRUD_TEMP/createForm.html.twig', [
            'database' => $database,
            'form' => $form,
            'basicdetail' => $basicdetail
        ]);
    }

    /**
     * @Route("/get-form", name="app_crud_form", methods: ['GET'])
     * @param Request $request
     * @return Response
     */
    #[Route('/get-form', name: 'get_form', methods: ['GET'])]

    public function getForm(Request $request): Response
    {
        $basicdetail = new BasicDetail();
        $basicForm = $this->createForm(BasicDetailFormType::class);
        $basicForm->handleRequest($request);

        return $this->render('CRUD_TEMP/addressFormDetail.html.twig', [
            // 'form' => $form->createView(),
            'basicdetail' => $basicdetail,
            'formBasic' => $basicForm
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_crud_edit", methods: ['GET', 'POST'])
     * @param Request $request
     * @return Response
     */
    #[Route('/{id}/edit', name: 'app_crud_edit', methods: ['GET', 'POST'])]
    public function editRecord(int $id,Request $request, Database $database, EntityManagerInterface $entityManager, SluggerInterface $slugger,DatabaseRepository $databaseRepository, BasicDetailRepository $basicRepository): Response
    {
        $databaseRepo = $databaseRepository->findOneBy(['id' => $id]);
        $basicDetail = $databaseRepo->getBasicDetailID()->toArray();
        $form = $this->createForm(CrudFormType::class, $database);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('image')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
            }
            $database->setImage($newFilename);
            $basicDetailData = $request->request->all('basic_detail_form');
            $length = count($basicDetailData['state']);
            for ($i = 1; $i <= $length; $i++) {
                $basicRepo = $basicRepository->findOneBy(['id' => $basicDetailData['id'][$i]]);
                $basicRepo->setState($basicDetailData['state'][$i]); // Adding 1 because array keys are 1-indexed
                $basicRepo->setDist($basicDetailData['dist'][$i]);
                $basicRepo->setZip($basicDetailData['zip'][$i]);
            }       
            $this->em->persist($database);
            try {
                $entityManager->flush();
            } catch (\Exception $e) {
                dump($e->getMessage());
                dump($e->getTrace());
            }

            return $this->redirectToRoute('app_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('CRUD_TEMP/editFormData.html.twig', [
            'database' => $database,
            'form' => $form,
            'basicDetail' =>$basicDetail
        ]);
    }

    /**
     * @Route("/data", name="app_crud_delete", methods: ['GET', 'POST'])
     * @param Request $request
     * @return Response
     */
    #[Route('/data', name: 'app_crud_delete', methods: ['GET', 'POST'])]
    public function deleteRecord(Request $request): Response
    {
        $id = $request->query->get('id');
        $entity = $this->em->getRepository(Database::class)->find($id);
        if ($entity) {
            $this->em->remove($entity);
            try {
                $this->em->flush();
            } catch (\Exception $e) {
                dump($e->getMessage());
                dump($e->getTrace());
            }
            return $this->redirectToRoute('app_crud_index', [], Response::HTTP_SEE_OTHER);
        } else {
            return new Response("Entity with ID $id not found", Response::HTTP_NOT_FOUND);
        }
    }
}
