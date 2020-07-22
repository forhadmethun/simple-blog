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
 * @Route("/admin/post", name="post.")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param Request $request
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(Request $request, PostRepository $postRepository)
    {
        $posts = $postRepository->findBy([
            'user' => $this->getUser()->getId()
        ]);
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/show/{id}", name="show")
     * @param $id
     * @param PostRepository $repository
     * @return JsonResponse
     */
    public function show($id, PostRepository $repository)
    {
        $post = $repository->find($id);
        return $this->render('post/show.html.twig', [
            'controller_name' => 'PostController',
            'post' => $post
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $post->setUser($this->getUser());
            $post->setCreatedAt(new \DateTime());
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Post created!');
            return $this->redirect($this->generateUrl('post.index'));
        }
        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @param $id
     * @param Request $request
     * @param PostRepository $repository
     * @return RedirectResponse
     */
    public function edit($id, Request $request,  PostRepository $repository)
    {
        $post = $repository->find($id);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Post edited!');
            return $this->redirect($this->generateUrl('post.index'));
        }
        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @param $id
     * @param PostRepository $repository
     * @return RedirectResponse
     */
    public function delete($id, PostRepository $repository)
    {
        $post = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        $this->addFlash('success', 'Post was removed!');
        return $this->redirect($this->generateUrl('post.index'));
    }
}
