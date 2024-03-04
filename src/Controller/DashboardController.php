<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Post;
use App\Security\PostVoter;
use App\Repository\PostRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;   
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;




#[Route('/api', name: 'api_')]
class DashboardController extends AbstractController
{
    /**
     * @param PostRepository $postRepository
     * @return JsonResponse
     */
    #[Route('/dashboard', name: 'app_dashboard')]
    // #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(TokenInterface $token,PostRepository $postRepository): JsonResponse
    {
        $user = $token->getUser();
        $user_Data = $postRepository->findAll();
        return $this->json([
            'message' => 'User Data',
            'data' => $user_Data,
            'token' => $user->getRoles(),
        ]);
    }

    /**
     * @param Request $request, ManagerRegistry $doctrine
     * @return JsonResponse
     */
    #[Route('/add_post', name: 'app_addPost')]
    public function registerPost(Request $request, ManagerRegistry $doctrine,AuthorizationCheckerInterface $authChecker): JsonResponse
    {
        $post = new Post();
         // Check if the user is allowed to edit the Data post
        if (!$authChecker->isGranted('post', $post)) {
            throw new AccessDeniedException('You are not allowed to add this post.');
        }

        $em = $doctrine->getManager();
        $PostReqData = json_decode($request->getContent(), true);
        if (!isset($PostReqData)) {
            return $this->json(['error' => 'Data is missing'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $post = new Post();
        $post->setMobile($PostReqData['mobile']);
        $post->setAge($PostReqData['age']);
        $post->setName($PostReqData['name']);
        $post->setAddress($PostReqData['address']);
        $em->persist($post);
        $em->flush();
        return $this->json([
            'message' => 'Post Created',    
            // 'data' => $PostReqData,
        ]);
    }
   
     /**
     * @param Request $request, ManagerRegistry $doctrine, int $id
     * @return JsonResponse
     */
    #[Route('/remove_post', name: 'app_removePost')]
    public function removePost(Request $request, ManagerRegistry $doctrine, AuthorizationCheckerInterface $authChecker): JsonResponse
    {

        $post = new Post();
        // Check if the user is allowed to edit the Data post
       if (!$authChecker->isGranted('edit', $post)) {
           throw new AccessDeniedException('You are not allowed to Delete this post.');
       }
       
        $em = $doctrine->getManager();
        $del_ID = json_decode($request->getContent(), true);
        $entity = $em->getRepository(Post::class)->find($del_ID['id']);
        if ($entity) {
            $em->remove($entity);
                $em->flush();
                return $this->json([
                    'message' => 'Post Deleted Successfully',
                    // 'data' => $PosSensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundletReqData,
                ]);
        }
        return $this->json([
            'message' => 'No Post Data',
        ]);
    }

     /**
     * @param Request $request, ManagerRegistry $doctrine
     * @return JsonResponse
     */
    #[Route('/edit_data', name: 'app_editPost')]
    // #[IsGranted('ROLE_ADMIN')]
    public function editPost(Request $request, ManagerRegistry $doctrine,PostRepository $postRepository, AuthorizationCheckerInterface $authChecker): JsonResponse
    {

        $post = new Post();
         // Check if the user is allowed to edit the Data post
        if (!$authChecker->isGranted('edit', $post)) {
            throw new AccessDeniedException('You are not allowed to Edit this post.');
        }
        
        $em = $doctrine->getManager();
            $edit_ID = json_decode($request->getContent(), true);

        if (!isset($edit_ID['id'])) {
            return $this->json(['error' => 'ID is missing'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $postRepo = $postRepository->findOneBy(['id' => $edit_ID['id']]);
        if (!$postRepo) {
            return $this->json(['error' => 'Post not found'], JsonResponse::HTTP_NOT_FOUND);
        }
            $postRepo->setName($edit_ID['name']); 
            $postRepo->setAge($edit_ID['age']);
            $postRepo->setAddress($edit_ID['address']);
            $postRepo->setMobile($edit_ID['mobile']);
        
        $em->flush();
       
        return $this->json([
            'message' => 'Post Updated',
            'data' => $postRepo
        ]);
   
    }




//   7+31+12

// 43 mint
}
