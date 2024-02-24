<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Database;
use App\Form\CrudFormType;
use App\Repository\DatabaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class CRUDController extends AbstractController
{
    private $em;
    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        $this->em = $entityManager;
    }

    
    #[Route('/', name: 'app_crud_index', methods: ['GET'])]
    public function index(DatabaseRepository $databaseRepository): Response
    {
        return $this->render(
            'CRUD_TEMP/indexCrud.html.twig',
            ['datashow' => $databaseRepository->findAll(),]
        );
    }

    #[Route('/register', name: 'app_crud_form', methods: ['GET', 'POST'])]
    public function crudForm(Request $request,  SluggerInterface $slugger,  PersistenceManagerRegistry $doctrine): Response
    {
        $database = new Database();
        $form = $this->createForm(CrudFormType::class, $database);
        $form->handleRequest($request);
        $formData = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('image')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
            }
            // try {
            //     $brochureFile->move(
            //         $this->getParameter('brochures_directory'),
            //         $newFilename
            //     );
            // } catch (FileException $e) {
            // }
            $database->setImage($newFilename);
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
        ]);
    }

    #[Route('/{id}/edit', name: 'app_crud_edit', methods: ['GET', 'POST'])]
    public function editRecord(Request $request, Database $database, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
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

            // $entityManager->flush();
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
        ]);
    }

    #[Route('/data', name: 'app_crud_delete', methods: ['GET', 'POST'])]
    public function deleteRecord(Request $request): Response
    {
        $id = $request->query->get('id');
        $entity = $this->em->getRepository(Database::class)->find($id);
        if ($entity) {
            $this->em->remove($entity);
            // $this->em->flush();
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
