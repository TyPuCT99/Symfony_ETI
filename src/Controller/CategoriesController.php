<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\Type\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CategoriesController extends AbstractController
{
    public function index(CategoryRepository $repository)
    {
        $categories = $repository->findAll();

        return $this->render('categories/index.html.twig', [
            'categories' => $categories
        ]);
    }

    public function postsByCategory(Category $category)
    {
        $posts = $category->getPosts();

        return $this->render('blogs/index.html.twig', [
            'posts' => $posts
        ]);
    }

    public function newCategory(Request $request, ManagerRegistry $doctrine)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $category = $form->getData();

            $manager = $doctrine->getManager();
            $manager->persist($category);
            $manager->flush();
            // ... perform some action, such as saving the task to the database

            $this->addFlash('notice', 'A new category has been added successfully!');

            return $this->redirectToRoute('categories.browse');
        }

        return $this->renderForm('categories/new.html.twig', [
            'form' => $form,
        ]);
    }
}