<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\SubCategory;
use App\Entity\Thread;
use App\Form\CategoryType;
use App\Form\PostType;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\SubCategoryRepository;
use App\Repository\ThreadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    public function __construct(
        Private EntityManagerInterface $em,
        Private CategoryRepository $categoryRepository,
        Private ThreadRepository $threadRepository,
        Private PostRepository $postRepository,
        Private PaginatorInterface $paginator
    ){}

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'categories' => $this->categoryRepository->findWithSubCategOrder(),
        ]);
    }

    #[Route('/{name}/threads', name: 'threads')]
    public function threads(SubCategory $subCategory): Response
    {
        return $this->render('home/threads.html.twig', [
            'subCateg' => $subCategory,
            'threads' => $this->threadRepository->findAllThreadBySubCateg($subCategory),
        ]);
    }

    #[Route('/threads/{subject}/posts', name: 'posts')]
    public function posts(string $subject, Request $request): Response
    {
        $thread = $this->threadRepository->findOneBy(['subject' => $subject]);
        $qb = $this->postRepository->findAllByThreadSubject($subject);
        $pagination = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 5);

        return $this->render('home/posts.html.twig', [
            'thread' => $thread,
            'paginator' => $pagination,
        ]);
    }
}
