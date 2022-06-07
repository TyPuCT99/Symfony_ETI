<?php

namespace App\Form\Type;

use App\Entity\Post;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    private CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories = $this->repository->findAll();

        $choices = [];

        foreach ($categories as $category) {
            $choices[] = [$category->getName() => $category->getId()];
        }

        $builder
            ->add('title', TextType::class)
            ->add('text', TextType::class)
            ->add('category_id', ChoiceType::class, [
                'choices' => $choices,
                'mapped' => false
            ])
            ->add('save',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}