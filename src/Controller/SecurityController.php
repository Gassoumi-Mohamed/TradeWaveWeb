<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email; 
use App\Entity\User ; 



class SecurityController extends AbstractController
{
    
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        // Récupérer les erreurs d'authentification, le dernier email saisi, etc.
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastEmail = $authenticationUtils->getLastUsername();
    
        // Vérifier si le formulaire de connexion a été soumis
        if ($request->isMethod('POST')) {
            // Récupérer les données soumises dans le formulaire
            $submittedEmail = $request->request->get('email');
            $submittedPassword = $request->request->get('password');
    
            // Rechercher l'utilisateur dans la base de données par email
            $userRepository = $this->getDoctrine()->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $submittedEmail]);
    
            // Vérifier si l'utilisateur existe et si le mot de passe est correct
            if ($user && $user->getPassword() === $submittedPassword) {
                // Vérifier le type de l'utilisateur
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
    
        // Afficher le formulaire de connexion avec les données appropriées
        return $this->render('security/login.html.twig', [
            'last_email' => $lastEmail,
            'error'      => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }



    /**
 * @Route("/verify", name="verify-email", methods={"POST","GET"})
 */
           public function verifyemail( MailerInterface $mailer): Response
          {
   
   $code=1;
        // $emailContent = $this->render('user/email.html.twig', ['code' => $code]);
        $emailContent = "test";

        $email = (new Email())
            ->from('yo.yotalent7@gmail.com') 
            ->to('Mohamed.Gassoumi@esprit.tn')
            ->subject('Confirmation de votre compte')
            ->html("test");

        $mailer->send($email);

       

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
   
    }


}





