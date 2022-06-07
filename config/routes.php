<?php

use App\Controller\AuthController;
use App\Controller\CategoriesController;
use App\Controller\CommentsController;
use App\Controller\PostsController;
use App\Controller\HomeController;
use App\Controller\RegistrationController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;


return function (RoutingConfigurator $routes) {

    $routes->add('home', '/')->controller(HomeController::class)->methods(['GET']);

    $routes->add('login', '/login')->controller([AuthController::class, 'showForm'])->methods(['GET', 'POST']);
    $routes->add('app_logout', '/logout')->methods(['GET']);
    $routes->add('register', '/register')->controller([RegistrationController::class, 'register'])->methods(['GET', 'POST']);

    $routes->add('posts.browse', '/posts')->controller([PostsController::class, 'index'])->methods(['GET']);
    $routes->add('posts.add', '/posts/add')->controller([PostsController::class, 'newPost'])->methods(['GET', 'POST']);
    $routes->add('posts.show', '/posts/{id}')->controller([PostsController::class, 'show'])->methods(['GET']);

    $routes->add('categories.browse', '/categories',)->controller([CategoriesController::class, 'index'])->methods(['GET']);
    $routes->add('categories.add', '/categories/add',)->controller([CategoriesController::class, 'newCategory'])->methods(['GET', 'POST']);
    $routes->add('categories.postsByCategory', '/categories/{category}',)->controller([CategoriesController::class, 'postsByCategory'])->methods(['GET']);

    $routes->add('comments.add', '/comments',)->controller([CommentsController::class, 'add'])->methods(['GET', 'POST']);
    $routes->add('comments.delete', '/comments/{id}/delete',)->controller([CommentsController::class, 'delete'])->methods(['GET', 'POST']);
    $routes->add('comments.edit', '/comments/{id}/update',)->controller([CommentsController::class, 'showUpdateForm'])->methods(['GET', 'POST']);
    $routes->add('comments.save', '/comments/save',)->controller([CommentsController::class, 'save'])->methods(['GET', 'POST']);

    $routes->add('test', 'test')->controller([AuthController::class, 'test'])->methods(['GET']);
};
