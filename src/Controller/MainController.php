<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="main")
     * @param PostRepository $repository
     * @return Response
     */
    public function index(PostRepository $repository)
    {
        return $this->redirect($this->generateUrl('blog'));
    }

    /**
     * @Route("/blog", name="blog")
     * @param Request $request
     * @param PostRepository $repository
     * @return Response
     */
    public function blog(Request $request, PostRepository $repository)
    {
        $searchParam = $request->query->get('search');
        if($searchParam!=null){
            $posts = $repository->searchByParameter($searchParam);
        }
        else{
            $posts = $repository->findAll();
        }
        return $this->render('home/index.html.twig', [
            'posts' => $posts
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
    public function commentCreate($id, Request $request,
                            PostRepository $postRepository,
                            CommentRepository $commentRepository
    )
    {
        $obj = json_decode($request->getContent());
        $commenterName = $obj->commenterName;
        $commenterEmail = $obj->commenterEmail;
        $commentBody = $obj->commentBody;

        $comment = new Comment();
        $comment->setCreatedAt(new \DateTime());
        $comment->setCommenterName($commenterName);
        $comment->setCommenterEmail($commenterEmail);
        $comment->setCommentBody($commentBody);
        $comment->setPost($postRepository->find($id));

        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        $comments = $commentRepository->findBy([
            'post' => $comment->getPost()->getId()
        ]);
        $arrayCollection = array();
        foreach($comments as $c) {
            $arrayCollection[] = array(
                'id' => $c->getId(),
                'commenterName' => $c->getCommenterName(),
                'commenterEmail' => $c->getCommenterEmail(),
                'commentBody' => $c->getCommentBody(),
                'createdAt' => $c->getCreatedAt(),
            );
        }
        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/blog/comment/delete/{id}", name="comment.delete")
     * @param $id
     * @param Request $request
     * @param PostRepository $postRepository
     * @param CommentRepository $commentRepository
     * @return JsonResponse
     */
    public function commentDelete($id, Request $request,
                            PostRepository $postRepository,
                            CommentRepository $commentRepository
    )
    {

        $comment = $commentRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        $comments = $commentRepository->findBy([
            'post' => $comment->getPost()->getId()
        ]);
        $arrayCollection = array();
        foreach($comments as $c) {
            $arrayCollection[] = array(
                'id' => $c->getId(),
                'commenterName' => $c->getCommenterName(),
                'commenterEmail' => $c->getCommenterEmail(),
                'commentBody' => $c->getCommentBody(),
                'createdAt' => $c->getCreatedAt(),
            );
        }
        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/blog/{id}", name="show")
     * @param $id
     * @param PostRepository $repository
     * @param CommentRepository $commentRepository
     * @return JsonResponse
     */
    public function show($id,
                         PostRepository $repository,
                         CommentRepository $commentRepository)
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




}
