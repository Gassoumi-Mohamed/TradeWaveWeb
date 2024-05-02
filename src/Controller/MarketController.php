<?php

namespace App\Controller;

use App\Entity\Market;
use App\Form\MarketType;
use App\Repository\MarketRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/market')]
class MarketController extends AbstractController
{
    private int $is_admin=0; 

    #[Route('/', name: 'app_market_index', methods: ['GET'])]
    public function index(MarketRepository $marketRepository): Response
    {   if ($this->is_admin==1)
        $route = "Back/market/index.html.twig"; 
        else
        $route = "Front/market/index.html.twig";
        return $this->render($route, [
            'markets' => $marketRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_market_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        $market = new Market();

        $form = $this->createForm(MarketType::class, $market);
        $market->getName() ; 
        $market->setBprice(0.00);
        $market->setMcap(0.00);
        $market->setRate(0.00);
        $market->setSprice(0.00);
        $market->setIDUser(0);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($market);
            $entityManager->flush();

            return $this->redirectToRoute('app_market_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Back/market/new.html.twig', [
            'market' => $market,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_market_show', methods: ['GET'])]
    public function show(Market $market): Response
    {   if ($this->is_admin==1)
        return $this->render('Back/market/show.html.twig', [
            'market' => $market,
        ]);
        else 
        return $this->render('Front/market/show.html.twig', [
            'market' => $market,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_market_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Market $market, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MarketType::class, $market);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_market_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->is_admin==1)
        return $this->renderForm('Back/market/edit.html.twig', [
            'market' => $market,
            'form' => $form,
        ]);
        else 
        return $this->renderForm('Front/market/edit.html.twig', [
            'market' => $market,
            'form' => $form,
        ]);
    }

     #[Route('/{id}', name: 'app_market_delete', methods: ['POST'])]
     public function delete(Request $request, Market $market, EntityManagerInterface $entityManager): Response
     {
         if ($this->isCsrfTokenValid('delete'.$market->getIDmarket(), $request->request->get('_token'))) {
             $entityManager->remove($market);
             $entityManager->flush();
         }

         return $this->redirectToRoute('app_market_index', [], Response::HTTP_SEE_OTHER);
     }
}
