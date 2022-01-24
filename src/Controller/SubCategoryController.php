<?php

namespace App\Controller;

use App\Entity\SubCategory;
use App\Form\SubCategoryType;
use App\Repository\SubCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/subCategory')]
class SubCategoryController extends AbstractController
{
    public function __construct(
        Private EntityManagerInterface $em,
        Private SubCategoryRepository $subCategoryRepository,
    ){}

    #[Route('/create', name: 'createSubCategory')]
    public function createCategory(Request $request): Response
    {
        $newSubCategory = new SubCategory();
        $form = $this->createForm(SubCategoryType::class, $newSubCategory);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($newSubCategory);
            $this->em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('sub_category/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
