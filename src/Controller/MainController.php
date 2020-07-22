<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="main")
     * @param PostRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(PostRepository $repository)
    {
        return $this->redirect($this->generateUrl('blog'));
    }

    /**
     * @Route("/blog", name="blog")
     * @param PostRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function blog(PostRepository $repository)
    {
        $posts = $repository->findAll();
        return $this->render('home/index.html.twig',[
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/blog/{id}", name="show")
     * @param $id
     * @param PostRepository $repository
     * @return JsonResponse
     */
    public function show($id, PostRepository $repository)
    {
        $post = $repository->find($id);
        if(is_null($post)){
            return $this->json('');
        }
        return $this->render('post/show.html.twig', [
            'controller_name' => 'PostController',
            'post' => $post
        ]);
    }
}
