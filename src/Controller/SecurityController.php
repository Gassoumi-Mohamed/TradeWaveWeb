<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email; use Doctrine\Persistence\ManagerRegistry;

use App\Entity\User ; 
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class SecurityController extends AbstractController
{
    
    #[Route(path: '/login', name: 'app_login')]
    public function login(SessionInterface $session,Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        // Récupérer les erreurs d'authentification, le dernier email saisi, etc.
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastEmail = $authenticationUtils->getLastUsername();
        $user = $session->get('user');
        if ($user){
            if ($user->getType() === 'Admin') {
                // Rediriger l'administrateur vers l'interface d'administration
                return $this->render('user/UserList.html.twig', [
                    'users' => $users, // Remplacez $users par la variable contenant les données des utilisateurs
                ]);
            } else {
                // Rediriger l'utilisateur vers son interface utilisateur
                return $this->render('Base.html.twig');
            }
        }else{ if ($request->isMethod('POST')) {
            // Récupérer les données soumises dans le formulaire
            $submittedEmail = $request->request->get('email');
            $submittedPassword = $request->request->get('password');
    
            // Rechercher l'utilisateur dans la base de données par email
            $userRepository = $this->getDoctrine()->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $submittedEmail]);
    
            // Vérifier si l'utilisateur existe et si le mot de passe est correct
            if ($user && $user->getPassword() === $submittedPassword) {
                // Vérifier le type de l'utilisateur
                $session->set('user', $user);
                if ($user->getType() === 'Admin') {
                    // Rediriger l'administrateur vers l'interface d'administration
                    return $this->render('user/UserList.html.twig', [
                        'users' => $users, // Remplacez $users par la variable contenant les données des utilisateurs
                    ]);
                } else {
                    // Rediriger l'utilisateur vers son interface utilisateur
                    return $this->render('Base.html.twig');
                }
            } else {
                // Échec de la connexion, afficher un message d'erreur
                $error = "Adresse email ou mot de passe incorrect";
            }
        }
    }
    
        // Afficher le formulaire de connexion avec les données appropriées
        return $this->render('security/login.html.twig', [
            'last_email' => $lastEmail,
            'error'      => $error,
        ]);
      
    }
    

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->set('user', null);

        return $this->redirectToRoute('app_login');
    }



  
       #[Route('/verify', name:'verify-email', methods:["POST","GET"])]
           public function verifyemail( SessionInterface $session,MailerInterface $mailer ,Request $request, ManagerRegistry $managerRegistry): Response
          {
            $user = $session->get('user');
            // $userEmail=$user.getEmail();

            $htmlContent = "<html>
    <head>
        <title>Email de confirmation</title>
    </head>
    <body>
        <p>Cher destinataire,{{ lname }} {{ fname }}</p>
        <p>Votre code à 6 chiffres est: <strong>{{ code }}</strong></p>
    </body>
    </html>";
            if ($request->isMethod('POST')) {
                $code_tep = $request->request->get('code');
                $savedCode = $session->get('code');
                if (!empty($code_tep) && !empty($savedCode)) {
                    if ($code_tep == $savedCode) {
                        $em = $managerRegistry->getManager();
                        $em->persist($user);
                        $em->flush();
                        return $this->redirectToRoute('app_login');
                    }
    
                }
            } else{
                $code = mt_rand(100000, 999999);
                $session->set('code', $code);
                $fname = $user->getNom();
                $lname = $user->getPrenom();
                $htmlContent = str_replace(['{{ code }}', '{{ fname }}', '{{ lname }}'], [$code, $fname, $lname], $htmlContent);
                $user = $session->get('user');
                $email = $user->getEmail();
                
                if (!empty($email)) {
                  $email = (new Email())
                  ->from('yo.yotalent7@gmail.com') 
                  ->to($email)  // Utilisez l'adresse e-mail récupérée du formulaire
                  ->subject('Confirmation de votre compte')
                  ->html($htmlContent);

                   $mailer->send($email);
                }
            }

    return $this->render('user/ValidationCompte.html.twig', [
        'email' => $user->getEmail(),
        'code' => $code
    ]);
    

}    

       


}





