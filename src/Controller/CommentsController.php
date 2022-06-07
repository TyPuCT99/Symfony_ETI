<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $repository)
    {
        $this->commentRepository = $repository;
    }

    public function add(Request $request, ManagerRegistry $doctrine, PostRepository $postRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $comment = new Comment();
        $comment->setBody($request->get('body'));
        $comment->setPostId($postRepository->find($request->get('post_id')));

        $this->getUser()->addComment($comment);

        $manager = $doctrine->getManager();
        $manager->persist($comment);
        $manager->flush();

        return $this->redirectToRoute('posts.show', ['id' => $request->get('post_id')]);
    }

    public function delete(Comment $comment)
    {
        $this->commentRepository->remove($comment);

        return $this->redirectToRoute('posts.browse');
    }

    public function showUpdateForm(Comment $comment)
    {
        return $this->render('comments/showUpdateForm.html.twig', [
            'comment' => $comment
        ]);
    }

    public function save(Request $request, ManagerRegistry $doctrine)
    {
        $comment = $this->commentRepository->find($request->get('comment_id'));

        $comment->setBody($request->get('body'));

        $manager = $doctrine->getManager();
        $manager->persist($comment);
        $manager->flush();

        return $this->redirectToRoute('posts.browse');
    }
}
