<?php

namespace App\Controller;

use App\Entity\User;
use App\Controller\UserController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SearchUserType; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UserRepository;




class UserListController extends AbstractController
{
  
    #[Route('/users', name: 'user_list')]
    private $userController;

    public function __construct(UserController $userController)
    {
        $this->userController = $userController;
    }
    public function userList(Request $request): Response
    {
        $searchTerm = $request->query->get('search'); // Récupère le terme de recherche depuis la requête GET

        // Si un terme de recherche est fourni, recherchez les utilisateurs correspondants, sinon récupérez tous les utilisateurs
        if ($searchTerm) {
            $users = $this->getDoctrine()->getRepository(User::class)->findBySearchTerm($searchTerm);
        } else {
            $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        }

        return $this->render('user/UserList.html.twig', [
            'users' => $users,
            'searchTerm' => $searchTerm, // Passez le terme de recherche à la vue pour l'affichage
        ]);
    }
    #[Route('/user/{id}', name: 'user_delete_list', methods: ['POST'])]
    public function deleteUser(Request $request, $id): Response
    {
        // Récupérer l'EntityManager
        $entityManager = $this->getDoctrine()->getManager();

        // Récupérer l'utilisateur en fonction de l'ID
        $user = $entityManager->getRepository(User::class)->find($id);

        // Vérifier si l'utilisateur existe
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        // Appeler la méthode de suppression de UserController
        $response = $this->userController->delete($request, $user, $entityManager);

        // Rediriger en fonction de la réponse de la suppression
        return $response;
    }



    #[Route('/Rechercher', name: 'rechercher', methods: ['POST'])]
    public function search(Request $request, UserRepository $userRepository): Response
    {
        // Récupérer le terme de recherche depuis la requête
        $searchTerm = $request->query->get('search');

        // Rechercher les utilisateurs par nom en utilisant le repository UserRepository
        $users = $userRepository->findByNom($searchTerm);

        // Faire quelque chose avec les utilisateurs trouvés (par exemple, les passer à un template Twig pour affichage)
        // ...

        return $this->render('user/UserList.html.twig', [
            'users' => $users,
        ]);
    }

// class UserListController extends AbstractController
// {
  
//         #[Route('/users', name: 'user_list')]
//     public function userList(Request $request): Response
//     {
//         $searchTerm = $request->query->get('search'); // Récupère le terme de recherche depuis la requête GET

//         // Si un terme de recherche est fourni, recherchez les utilisateurs correspondants, sinon récupérez tous les utilisateurs
//         if ($searchTerm) {
//             $users = $this->getDoctrine()->getRepository(User::class)->findBySearchTerm($searchTerm);
//         } else {
//             $users = $this->getDoctrine()->getRepository(User::class)->findAll();
//         }

//         return $this->render('user/UserList.html.twig', [
//             'users' => $users,
//             'searchTerm' => $searchTerm, // Passez le terme de recherche à la vue pour l'affichage
//         ]);
//     }

//     #[Route('/user/delete/{id}', name: 'user_delete')]
//     public function delete(int $id, EntityManagerInterface $entityManager): Response
//     {
//         $user = $entityManager->getRepository(User::class)->find($id);
    
//         if (!$user) {
//             throw $this->createNotFoundException('Utilisateur non trouvé');
//         }
    
//         $entityManager->remove($user);
//         $entityManager->flush();
    
//         return $this->redirectToRoute('user_list');
//     }
    
    
// }
}