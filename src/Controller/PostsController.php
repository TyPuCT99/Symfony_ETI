<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\Type\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PostsController extends AbstractController
{
    /**
     */
    public function index(PostRepository $postRepository)
    {
        $posts = $postRepository->findAll();

        return $this->render('blogs/index.html.twig', [
            'posts' => $posts
        ]);
    }

    public function show(Post $post)
    {
        $comments = $post->getComments();

        return $this->render('blogs/view.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

    public function newPost(Request $request, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $post = $form->getData();

            $post->setUserId($this->getUser());

            $manager = $doctrine->getManager();
            $manager->persist($post);
            $manager->flush();
            // ... perform some action, such as saving the task to the database

            $this->addFlash('notice', 'A new post has been added successfully!');

            return $this->redirectToRoute('posts.browse');
        }

        return $this->renderForm('blogs/new.html.twig', [
            'form' => $form,
        ]);
    }
}