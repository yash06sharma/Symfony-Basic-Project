<?php

namespace App\Controller;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use App\Repository\BasicDetailRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DatabaseRepository;
use App\Event\ProductEventSubscriber;
use App\Form\BasicDetailFormType;
use App\Event\ProductCreateEvent;
use App\Event\ProductDeleteEvent;
use App\Event\ProductUpdateEvent;
use App\Entity\BasicDetail;
use App\Form\CrudFormType;
use App\Entity\Database;
use Symfony\Component\Filesystem\Path;
use App\Event\BasicEventListner;


class CrudController extends AbstractController
{
    /**
     * $em Global Entity Manager Variable
     */
    private $em;
    /**
     * EventDispatcherInterface $eventDispatcher
     */
    private $eventDispatcher;
    /**
     * To initialization
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/", name="app_crud_index", methods: ['GET'])
     * @param DatabaseRepository $databaseRepository
     * @param TranslatorInterface $translator
     * @return Response
     */

    #[Route('/', name: 'app_crud_index', methods: ['GET'])]
    public function index(DatabaseRepository $databaseRepository,TranslatorInterface $translator,BasicEventListner $bslistner): Response
    {
        $bslistner->onCheckEventMethod();
        $translatedMessage = $translator->trans('hello', locale: 'fr');
        return $this->render(
            'CrudTemp/indexCrud.html.twig',
            ['datashow' => $databaseRepository->findAll(),
            'translatedMessage' => $translatedMessage]);
    }

    /**
     * @Route("/register", name="app_crud_form", methods: ['GET',['POST']])
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param PersistenceManagerRegistry $doctrine
     * @param DatabaseRepository $databaseRepository
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

            //   try {
            //     $brochureFile->move(
            //         $this->getParameter('brochures_directory'),
            //         $newFilename
            //     );
            // } catch (FileException $e) {
            // }

            $basicDetailData = $request->request->all('basic_detail_form');
            if($basicDetailData){
                $length = count($basicDetailData['state']);
                for ($i = 0; $i < $length; $i++) {
                    $basicdetail = new BasicDetail();
                    $basicdetail->setState($basicDetailData['state'][$i + 1]);
                    $basicdetail->setDist($basicDetailData['dist'][$i + 1]);
                    $basicdetail->setZip($basicDetailData['zip'][$i + 1]);
                    $basicdetail->setDatabaseUserId($database);
                    $this->em->persist($basicdetail);
                }
            }
            $this->em->persist($database);
            try {
                $this->em->flush();
            } catch (\Exception $e) {
                dump($e->getMessage());
                dump($e->getTrace()); 
            }
            //-----------------------Event & Subscribe---------------------
            $event = new ProductCreateEvent();
            $this->eventDispatcher->addSubscriber(new ProductEventSubscriber());
            $this->eventDispatcher->dispatch($event, ProductCreateEvent::NAME);

            //-----------------------End Event $ Subscriner----------------------
            return $this->redirectToRoute('app_crud_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CrudTemp/createForm.html.twig', [
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
        return $this->render('CrudTemp/addressFormDetail.html.twig', [
            'basicdetail' => $basicdetail,
            'formBasic' => $basicForm
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_crud_edit", methods: ['GET', 'POST'])
     * @param Request $request
     * @param Database $database
     * @param EntityManagerInterface $entityManager
     * @param SluggerInterface slugger
     * @param DatabaseRepository databaseRepository
     * @param BasicDetailRepository basicRepository
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
            //-----------------------Event & Subscribe---------------------

                $event = new ProductUpdateEvent();
                $this->eventDispatcher->addSubscriber(new ProductEventSubscriber());
                $this->eventDispatcher->dispatch($event, ProductUpdateEvent::NAME);
            //-----------------------End Event & Subscribe---------------------

            return $this->redirectToRoute('app_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('CrudTemp/editFormData.html.twig', [
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
                 //-----------------------Event & Subscribe---------------------
            $event = new ProductDeleteEvent();
            $this->eventDispatcher->addSubscriber(new ProductEventSubscriber());
            $this->eventDispatcher->dispatch($event, ProductDeleteEvent::NAME);
                  //-----------------------End Event & Subscribe---------------------
            return $this->redirectToRoute('app_crud_index', [], Response::HTTP_SEE_OTHER);
        } else {
            return new Response("Entity with ID $id not found", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Responsible for FileSystem Operation
     * @return Response
     */
    #[Route('/fileoperation', name: 'app_crud_fileOperation', methods: ['GET'])]
    public function fileOperation(): Response
    {
        $fsObject = new Filesystem();
        $current_dir_path = getcwd();
        // try {
        //     $new_dir_path = $current_dir_path . "/work";
        //     if (!$fsObject->exists($new_dir_path))
        //     {
        //         $old = umask(0);
        //         $fsObject->mkdir($new_dir_path, 0775);
        //         $fsObject->chown($new_dir_path, "www-data");
        //         $fsObject->chgrp($new_dir_path, "www-data");
        //         umask($old);
        //     }
        //--------------------End Create a Folder-------------
        // try {
        //     // $new_file_path = $current_dir_path . "/foo/bar.txt";
        //     $new_file_path = $current_dir_path . "/work/yash.txt";

        //     if (!$fsObject->exists($new_file_path))
        //     {
        //         $fsObject->touch($new_file_path);
        //         $fsObject->chmod($new_file_path, 0777);
        //         $fsObject->dumpFile($new_file_path, "Custom File create using filesystem.\n");
        //         $fsObject->appendToFile($new_file_path, "append method for added to the end of the file.\n");
        //     }
        //---------------End File Create in Forlder---------------
        // try {
        //     $src_dir_path = $current_dir_path . "/foo";
        //     $dest_dir_path = $current_dir_path . "/foo_copy";
        //     if (!$fsObject->exists($dest_dir_path))
        //     {
        //         $fsObject->mirror($src_dir_path, $dest_dir_path);
        //     }
        //     //--------------------Create mirror of any folder and file-----------
        try {
            $arr_dirs = array(
                // $current_dir_path . "/foo",
                $current_dir_path . "/foo_copy"
            );
            $fsObject->remove($arr_dirs);
            //-----------------------Remove Folder and File-------------------
        } catch (IOExceptionInterface $exception) {
            echo "Error creating directory at". $exception->getPath();
        }
        return $this->redirectToRoute('app_crud_index', [], Response::HTTP_SEE_OTHER);
    }

}
