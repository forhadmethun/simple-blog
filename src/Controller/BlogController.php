<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     * @param Request $request
     * @param PostRepository $repository
     * @return Response
     */
    public function blog(
        Request $request,
        PostRepository $repository
    )
    {
        $searchParam = $request->query->get('search');
        if ($searchParam != null) {
            $posts = $repository->searchByParameter($searchParam);
        } else {
            $posts = $repository->findAll();
        }
        return $this->render('home/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/blog/{id}", name="show")
     * @param $id
     * @param PostRepository $repository
     * @param CommentRepository $commentRepository
     * @return JsonResponse
     */
    public function show(
        $id,
        PostRepository $repository,
        CommentRepository $commentRepository
    )
    {
        $post = $repository->find($id);
        $post->setComments($commentRepository->findBy([
            'post' => $id
        ]));
        return $this->render('post/show.html.twig', [
            'controller_name' => 'PostController',
            'post' => $post
        ]);
    }


    /**
     * @Route("/blog/comment/{id}", name="comment.create")
     * @param $id
     * @param Request $request
     * @param PostRepository $postRepository
     * @param CommentRepository $commentRepository
     * @return JsonResponse
     */
    public function commentCreate(
        $id,
        Request $request,
        PostRepository $postRepository,
        CommentRepository $commentRepository
    )
    {
        $obj = json_decode($request->getContent());

        $comment = new Comment();
        $comment->setCreatedAt(new \DateTime());
        $comment->setCommenterName($obj->commenterName);
        $comment->setCommenterEmail($obj->commenterEmail);
        $comment->setCommentBody($obj->commentBody);
        $comment->setPost($postRepository->find($id));

        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        return $this->getAllCommentsByPostIdAsJsonResponse(
            $commentRepository,
            $comment->getPost()->getId()
        );
    }

    /**
     * @Route("/blog/comment/delete/{id}", name="comment.delete")
     * @param $id
     * @param CommentRepository $commentRepository
     * @return JsonResponse
     */
    public function commentDelete(
        $id,
        CommentRepository $commentRepository
    )
    {

        $comment = $commentRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        return $this->getAllCommentsByPostIdAsJsonResponse(
            $commentRepository,
            $comment->getPost()->getId()
        );
    }

    public function getAllCommentsByPostIdAsJsonResponse(
        CommentRepository $commentRepository,
        $id
    ){
        $commentCollection = $commentRepository->getAllCommentsAsCollectionByPostId($id);
        return new JsonResponse($commentCollection);
    }
}
