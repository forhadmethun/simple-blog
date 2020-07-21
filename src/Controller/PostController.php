<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/post", name="post.")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository)
    {
        $posts = $postRepository->findAll();
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $posts
        ]);
    }


    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request){
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
//            dump($post);
        $em->persist($post);
        $em->flush();
            return $this->redirect($this->generateUrl('post.index'));


        }
        $post->setTitle('This is a title');

//        return new Response('Post was created');
        return $this->render('post/create.html.twig', [
           'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/create/{text}", name="create-text")
     * @param $text
     * @return Response
     */
    public function createText($text){
        $post = new Post();
        $post->setTitle($text);
        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();
        return new Response('Post was created');
    }

    /**
     * @Route("/show/{id}", name="show")
     * @param $id
     * @param PostRepository $repository
     * @return JsonResponse
     */
    public function show($id, PostRepository $repository){
        $post = $repository->find($id);
        return $this->render('post/show.html.twig', [
            'controller_name' => 'PostController',
            'post' => $post
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @param $id
     * @param PostRepository $repository
     * @return RedirectResponse
     */
    public function delete($id, PostRepository $repository){
        $post = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        $this->addFlash('success', 'Post was removed!');
        return $this->redirect($this->generateUrl('post.index'));
    }
}
