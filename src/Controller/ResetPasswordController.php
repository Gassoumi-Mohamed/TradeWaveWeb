<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mime\Email;

use Doctrine\Persistence\ManagerRegistry;



// #[Route('/reset-password')]
 class ResetPasswordController extends AbstractController
 {
//     use ResetPasswordControllerTrait;

//     // public function __construct{
//     //     private ResetPasswordHelperInterface $resetPasswordHelper,
//     //     private EntityManagerInterface $entityManager
//     // } 

//     /**
//      * Display & process form to request a password reset.
//      */
//     #[Route('', name: 'app_forgot_password_request')]
//     public function request(Request $request, MailerInterface $mailer, TranslatorInterface $translator): Response
//     {
//         $form = $this->createForm(ResetPasswordRequestFormType::class);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             return $this->processSendingPasswordResetEmail(
//                 $form->get('email')->getData(),
//                 $mailer,
//                 $translator
//             );
//         }

//         return $this->render('reset_password/request.html.twig', [
//             'requestForm' => $form->createView(),
//         ]);
//     }

//     /**
//      * Confirmation page after a user has requested a password reset.
//      */
//     #[Route('/check-email', name: 'app_check_email')]
//     public function checkEmail(): Response
//     {
//         // Generate a fake token if the user does not exist or someone hit this page directly.
//         // This prevents exposing whether or not a user was found with the given email address or not
//         if (null === ($resetToken = $this->getTokenObjectFromSession())) {
//             $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
//         }

//         return $this->render('reset_password/check_email.html.twig', [
//             'resetToken' => $resetToken,
//         ]);
//     }

//     /**
//      * Validates and process the reset URL that the user clicked in their email.
//      */
//     #[Route('/reset/{token}', name: 'app_reset_password')]
//     public function reset(Request $request, UserPasswordHasherInterface $passwordHasher, TranslatorInterface $translator, string $token = null): Response
//     {
//         if ($token) {
//             // We store the token in session and remove it from the URL, to avoid the URL being
//             // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
//             $this->storeTokenInSession($token);

//             return $this->redirectToRoute('app_reset_password');
//         }

//         $token = $this->getTokenFromSession();
//         if (null === $token) {
//             throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
//         }

//         try {
//             $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
//         } catch (ResetPasswordExceptionInterface $e) {
//             $this->addFlash('reset_password_error', sprintf(
//                 '%s - %s',
//                 $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_VALIDATE, [], 'ResetPasswordBundle'),
//                 $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
//             ));

//             return $this->redirectToRoute('app_forgot_password_request');
//         }

//         // The token is valid; allow the user to change their password.
//         $form = $this->createForm(ChangePasswordFormType::class);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             // A password reset token should be used only once, remove it.
//             $this->resetPasswordHelper->removeResetRequest($token);

//             // Encode(hash) the plain password, and set it.
//             $encodedPassword = $passwordHasher->hashPassword(
//                 $user,
//                 $form->get('plainPassword')->getData()
//             );

//             $user->setPassword($encodedPassword);
//             $this->entityManager->flush();

//             // The session is cleaned up after the password has been changed.
//             $this->cleanSessionAfterReset();

//             return $this->redirectToRoute('app_home');
//         }

//         return $this->render('reset_password/reset.html.twig', [
//             'resetForm' => $form->createView(),
//         ]);
//     }

//     private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer, TranslatorInterface $translator): RedirectResponse
//     {
//         $user = $this->entityManager->getRepository(User::class)->findOneBy([
//             'email' => $emailFormData,
//         ]);

//         // Do not reveal whether a user account was found or not.
//         if (!$user) {
//             return $this->redirectToRoute('app_check_email');
//         }

//         try {
//             $resetToken = $this->resetPasswordHelper->generateResetToken($user);
//         } catch (ResetPasswordExceptionInterface $e) {
//             // If you want to tell the user why a reset email was not sent, uncomment
//             // the lines below and change the redirect to 'app_forgot_password_request'.
//             // Caution: This may reveal if a user is registered or not.
//             //
//             // $this->addFlash('reset_password_error', sprintf(
//             //     '%s - %s',
//             //     $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_HANDLE, [], 'ResetPasswordBundle'),
//             //     $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
//             // ));

//             return $this->redirectToRoute('app_check_email');
//         }

//         $email = (new TemplatedEmail())
//             ->from(new Address('Mohamed.Gassoumi@esprit.tn', 'app_login'))
//             ->to($user->getEmail())
//             ->subject('Your password reset request')
//             ->htmlTemplate('reset_password/email.html.twig')
//             ->context([
//                 'resetToken' => $resetToken,
//             ])
//         ;

//         $mailer->send($email);

//         // Store the token object in session for retrieval in check-email route.
//         $this->setTokenObjectInSession($resetToken);

//         return $this->redirectToRoute('app_check_email');
//     }

    #[Route('/email', name: 'app_email')]
    public function email(Request $request): Response
    {
        return $this->render('reset_password/emailOTP.html.twig');
    }

    #[Route('/verifieremail', name: 'app_verifyemail')]
    public function verifyEmail(Request $request, ManagerRegistry $doctrine, SessionInterface $session, MailerInterface $mailer): Response
    {
        $email = $request->request->get('email');
        $entityManager = $doctrine->getManager();
        $user= $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $this->addFlash('error', 'Email ne correspond pas.');
            return $this->redirectToRoute('app_email' );
        }

        $userId = $user->getId();
        $OTP=rand(1000, 9999);

        // $OTP=1234;

        $emailOTP = (new Email())
            ->from(new Address('TradeWave.support@gmail.com', 'TradeWave Support'))
            ->to($email)
            ->subject('Demande de réinitialisation de mot de passe')
            ->html('<p>Bonjour,</p><p>Vous avez demandé la réinitialisation de votre mot de passe. 
            Voici votre code de validation : <strong>' . $OTP . '</strong>.</p><p>Cordialement,<br>TradeWave Support</p>');
        $mailer->send($emailOTP);
        
        

        $session->set('userId', $userId);
        $session->set('OTP', $OTP);
        $session->set('email',$email);

        return $this->render('reset_password/OTP.html.twig');
    }

    #[Route('/otp', name: 'app_otp')]
    public function otp(SessionInterface $session): Response
    {
        return $this->render('reset_password/OTP.html.twig'
        // , [ 'email'=>$email, 'OTP'=>$OTP ]
    );
    }

    #[Route('/verifierotp', name: 'app_verifyotp')]
    public function verifyOtp(Request $request, SessionInterface $session): Response
    {
        $otp1 = $request->request->get('otp1');
        $otp2 = $request->request->get('otp2');
        $otp3 = $request->request->get('otp3');
        $otp4 = $request->request->get('otp4');
        $userOtp = intval($otp1 . $otp2 . $otp3 . $otp4);
        $session->set('userOTP', $userOtp);

        $OTPFromSession = $session->get('OTP');
        $email = $session->get('email');

        if($userOtp !== $OTPFromSession){
            $this->addFlash('error', 'Email ne correspond pas.');
            return $this -> render('reset_password/OTP.html.twig', ['email' =>$email,  'OTP'=>$OTPFromSession] );
        }

        return $this->render('reset_password/modifypassword.html.twig');
    }

    #[Route('/motdepasse', name: 'app_password')]
    public function Motdepasse(SessionInterface $session): Response
    {
        return $this->render('reset_password/modifypassword.html.twig');
    }


    #[Route('/modifiermotdepasse', name: 'app_modifypassword')]
    public function modifierMotdepasse(Request $request, SessionInterface $session, ManagerRegistry $doctrine): Response
    {
        $userId = $session->get('userId');
        $entityManager = $doctrine->getManager();
        $user= $entityManager->getRepository(User::class)->find($userId);
        $password = $request->request->get('password');
        if ($password === null) {
            // $flashy->success('Invalid Mot de passe ', 'http://your-awesome-link.com');
            return $this->redirectToRoute('app_password');
        }
        $user->setPassword($password);
        $session->invalidate();
        $entityManager->flush();

        // $flashy->success('Votre mot de passe a été changé avec succès.');
        $session->set('flashy_message', 'Votre mot de passe a été changé avec succès.');
        return $this->redirectToRoute('app_login');
    }
}
