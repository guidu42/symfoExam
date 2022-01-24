<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Thread;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\ThreadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    public function __construct(
        Private PostRepository $postRepository,
        Private ThreadRepository $threadRepository,
        Private EntityManagerInterface $em
    ){}

    #[Route('/thread/{subject}/create', name: 'createPost')]
    public function create(Request $request, string $subject): Response
    {
        $newPost = new Post();
        $form = $this->createForm(PostType::class, $newPost);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $newPost->setCreatedAt(new \DateTime());
            $newPost->setUser($this->getUser());
            $newPost->setThread($this->threadRepository->findFullBySubject($subject));
            $this->em->persist($newPost);
            $this->em->flush();

            return $this->redirectToRoute('posts', ['subject' => $subject]);
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
            'subject' => $subject
        ]);
    }

    #[Route('/thread/{subject}/edit/{id}', name: 'editPost')]
    public function edit(Request $request, string $subject, string $id): Response
    {
        $post = $this->postRepository->find($id);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($post);
            $this->em->flush();

            return $this->redirectToRoute('posts', ['subject' => $subject]);
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
            'subject' => $subject
        ]);
    }

    #[Route('/thread/{subject}/delete/{id}', name: 'post_delete')]
    public function delete(Request $request, string $subject, string $id): Response
    {
        $post = $this->postRepository->find($id);
        $this->em->remove($post);
        $this->em->flush();

        return $this->redirectToRoute('posts', ['subject' => $subject]);
    }

    #[Route('/{subject}/post/{id}/addUpVote', name: 'post_add_upVote')]
    public function addUpVote(string $subject, Post $post): Response
    {
        $post->setUpVote($post->getUpVote() + 1);
        $this->em->persist($post);
        $this->em->flush();
        return $this->redirectToRoute('posts', ['subject' => $subject]);
    }

    #[Route('/{subject}/post/{id}/addDownVote', name: 'post_add_downVote')]
    public function addDownVote(string $subject, Post $post): Response
    {
        $post->setDownVote($post->getDownVote() + 1);
        $this->em->persist($post);
        $this->em->flush();
        return $this->redirectToRoute('posts', ['subject' => $subject]);
    }
}
