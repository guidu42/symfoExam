<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(
        Private EntityManagerInterface $em,
        Private UserRepository $userRepository,
        Private PaginatorInterface $paginator,
        Private PostRepository $postRepository
    ){}

    #[Route('/user/{nickName}', name: 'user')]
    public function index(string $nickName, Request $request): Response
    {
        $user = $this->userRepository->findOneBy(['nickName' => $nickName]);
        $ownUserProfil = false;
        $qb = $this->postRepository->findFullByUser($user);
        $pagination = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 5);

        if($this->getUser() == $user){
            $ownUserProfil = true;
        }
        return $this->render('user/index.html.twig', [
            'ownUserProfil' => $ownUserProfil,
            'user' => $user,
            'paginator' => $pagination
        ]);
    }

    #[Route('/user/{nickName}/edit', name: 'user_edit')]
    public function edit(string $nickName, Request $request):Response
    {
        $user = $this->userRepository->findOneBy(['nickName' => $nickName]);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('user', ['nickName' => $user->getNickName()]);
        }

        return $this->render('user/edit.html.twig', [
           'form' => $form->createView(),
        ]);

    }
}
