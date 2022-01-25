<?php

namespace App\Controller;

use App\Entity\Thread;
use App\Form\SubCategoryType;
use App\Form\ThreadType;
use App\Repository\SubCategoryRepository;
use App\Repository\ThreadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/thread')]
class ThreadController extends AbstractController
{
    public function __construct(
        Private EntityManagerInterface $em,
        Private ThreadRepository $threadRepository,
        Private SubCategoryRepository $subCategoryRepository
    ){}

    #[Route('/create/{subCategId}', name: 'createThread')]
    public function createCategory(Request $request, string $subCategId): Response
    {

        if($this->getUser() == null){
            return $this->redirectToRoute('app_login');
        }

        $newThread = new Thread();
        $form = $this->createForm(ThreadType::class, $newThread);
        $form->handleRequest($request);
        $subCateg = $this->subCategoryRepository->find($subCategId);
        if($form->isSubmitted() && $form->isValid()){
            $newThread->setCreatedAt(new \DateTime());
            $newThread->setUser($this->getUser());
            $newThread->setSubCategory($subCateg);

            $this->em->persist($newThread);
            $this->em->flush();

            return $this->redirectToRoute('threads', ['name' => $subCateg->getName()]);
        }

        return $this->render('thread/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
