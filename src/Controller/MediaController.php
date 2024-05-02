<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;


#[Route('/media')]
class MediaController extends AbstractController
{
    #[Route('/', name: 'app_media_index', methods: ['GET'])]
    public function index(MediaRepository $mediaRepository): Response
    {
        return $this->render('media/index.html.twig', [
            'media' => $mediaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_media_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $medium = new Media();
        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $imageFile = $form->get('imagePath')->getData();
    
            if ($imageFile) {
                // Generate a unique name for the file
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
    
                // Move the file to the directory where your images are stored
                $imageFile->move(
                    $this->getParameter('public_directory').'/uploads',
                    $newFilename
                );
    
                // Store the file name in the entity
                $medium->setImagePath('/uploads/'.$newFilename);
            }
    
            // Persist the entity
            $entityManager->persist($medium);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_media_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('media/new.html.twig', [
            'medium' => $medium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_media_show', methods: ['GET'])]
    public function show(Media $medium): Response
    {
        return $this->render('media/show.html.twig', [
            'medium' => $medium,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_media_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Media $medium, EntityManagerInterface $entityManager, Filesystem $filesystem): Response
{
    $form = $this->createForm(MediaType::class, $medium);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle file upload
        $imageFile = $form->get('imagePath')->getData();

        if ($imageFile) {
            // Generate a unique name for the file
            $newFilename = uniqid().'.'.$imageFile->guessExtension();

            // Move the file to the directory where your images are stored
            $imageFile->move(
                $this->getParameter('public_directory').'/uploads',
                $newFilename
            );

            // Store the file name in the entity
            $medium->setImagePath('/uploads/'.$newFilename);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_media_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('media/edit.html.twig', [
        'medium' => $medium,
        'form' => $form->createView(),
    ]);
}

    #[Route('/{id}', name: 'app_media_delete', methods: ['POST'])]
    public function delete(Request $request, Media $medium, EntityManagerInterface $entityManager, Filesystem $filesystem): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medium->getId(), $request->request->get('_token'))) {
            // Delete the associated image file
            $imagePath = $medium->getImagePath();
            if ($imagePath && $filesystem->exists($imagePath)) {
                $filesystem->remove($imagePath);
            }
    
            // Remove the entity
            $entityManager->remove($medium);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_media_index', [], Response::HTTP_SEE_OTHER);
    }
}
