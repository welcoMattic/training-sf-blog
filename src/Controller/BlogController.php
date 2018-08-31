<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostTranslation;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\PostTranslationRepository;
use App\Service\Calculator;
use App\Service\ReadingTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index(Calculator $calculator): Response
    {
        $result = $calculator->addition(23, 12);

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'result' => $result,
        ]);
    }

    /**
     * @Route("/blog", name="blog")
     */
    public function list(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('blog/list.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/blog/{slug}", name="blog_show", methods={"GET"})
     */
    public function show(Request $request, string $slug, ReadingTime $readingTimeService, PostTranslationRepository $postTranslationRepository): Response
    {
        /** @var PostTranslation $postTranslation */
        $postTranslation = $postTranslationRepository->findOneBy([
            'slug' => $slug,
        ]);

        $readingTime = $readingTimeService->computeReadingTime($postTranslation->getBody());

        return $this->render('blog/show.html.twig', [
            'postTranslation' => $postTranslation,
            'readingTime' => $readingTime,
        ]);
    }

    /**
     * @Route("/blog/post/create", name="blog_post_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager, \Swift_Mailer $mailer): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $entityManager->persist($post);
                $entityManager->flush();

                $this->addFlash('success', 'Article créé avec succès !');

                if ($post->getIsPublished()) {
                    $message = new \Swift_Message();
                    $message
                        ->setSubject('Un nouvel article a été publié')
                        ->setBody($post->getBody())
                        ->setFrom('editor@jolicode.com')
                        ->setTo('bill.gates@apple.org');

                    $mailer->send($message);
                }
            }
        }

        return $this->render('blog/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
