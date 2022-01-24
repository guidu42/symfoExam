<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    public function __construct(
        Private EntityManagerInterface $em,
        Private CategoryRepository $categoryRepository,
    ){}

    #[Route('/create', name: 'createCategory')]
    public function createCategory(Request $request): Response
    {
        $newCategory = new Category();
        $form = $this->createForm(CategoryType::class, $newCategory);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($newCategory);
            $this->em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('category/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
