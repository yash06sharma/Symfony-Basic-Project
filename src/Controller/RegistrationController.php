<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface as CsrfTokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\RefreshToken;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;


#[Route('/api', name: 'api_')]
class RegistrationController extends AbstractController
{

    public function __construct(
        private RequestStack              $requestStack,
        private TokenStorageInterface     $tokenStorage,
        private CsrfTokenStorageInterface $csrfTokenStorage,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    #[Route('/registration', name: 'app_registration', methods: 'post')]
    public function index(ManagerRegistry $doctrine, Request $request,
             UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent(), true);
        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword($user,$decoded['password']);
        $user->setPassword($hashedPassword);
        $user->setRoles($decoded['roles']);
        $user->setEmail($decoded['email']);
        $user->setName($decoded['name']);
        $user->setAddress($decoded['address']);
        $em->persist($user);
        $em->flush();
        return $this->json([
            'message' => 'Registered Successfully',
            // 'data' => $roles,
        ]);
    }

    /**
     * @param ManagerRegistry $doctrine, JWTTokenManagerInterface $JWTManager,Request $request,UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    #[Route('/login', name: 'app_login', methods: 'post')]
    public function login(ManagerRegistry $doctrine, JWTTokenManagerInterface $JWTManager,
        Request $request,UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $decoded = json_decode($request->getContent(), true);
        $user = $doctrine->getRepository(User::class)->findOneBy(['email' => $decoded['email']]);
        if (!$user || !$passwordHasher->isPasswordValid($user, $decoded['password'])) {
            return $this->json(['error' => 'Invalid username or password'], Response::HTTP_UNAUTHORIZED);
        }
        $token = $JWTManager->create($user);

        return $this->json([
            'message' => 'Login Successfully',
            'token' => $token,
        ]);
    }



    #[Route('/logout', name: 'app_logout')]
    public function appLogout(TokenStorageInterface $tokenStorage, JWTTokenManagerInterface $JWTManager): JsonResponse
    {
    $tokenString = $this->extractJwtTokenFromAuthorizationHeader();
    $tokenStorage->setToken(null);

    return new JsonResponse([
        'message' => 'Logout successfully',
        'token' => $tokenString
    ]);
    }
        
    private function extractJwtTokenFromAuthorizationHeader(): ?string
{
    $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
    
    if ($authorizationHeader && preg_match('/Bearer\s+(.*)$/i', $authorizationHeader, $matches)) {
        return $matches[1];
    }
    
    return null;
}




    #[Route('/token/refresh', name: 'app_log_refresh')]
    public function app_log_refresh()
    {
        // $user = $this->getUser();
        // $jwtToken = $this->get('lexik_jwt_authentication.jwt_manager')->create($user);
        // $response = new JWTAuthenticationSuccessResponse($jwtToken);

        // $event = new AuthenticationSuccessEvent(['token' => $jwtToken], $user, $response);
        // $dispatcher = $this->get('event_dispatcher');
        // $dispatcher->dispatch(Events::AUTHENTICATION_SUCCESS, $event);
        // $response->setData($event->getData());

        // return $response;
        }

}


