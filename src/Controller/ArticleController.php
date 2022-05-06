<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Post;
use App\Service\ArticleApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    private ArticleApiService $apiService;
    private EntityManagerInterface $entityManager;

    public function __construct(ArticleApiService $apiService, EntityManagerInterface $entityManager)
    {
        $this->apiService = $apiService;
        $this->entityManager = $entityManager;
    }


    public function addArticle():void
    {
        $articles = $this->apiService->getArticle();
        foreach ($articles as $article) {

            $author = new Author();
            $author->setName($article['name']);
            $this->entityManager->persist($author);

            foreach ($article as $key => $value) {
                $post = new Post();
                $post->setAuthor($author);
                $title = $this->searchPostData($article, 'title', $key);
                if (!empty($title)) {
                    $post->setTitle($title);
                    $this->entityManager->persist($post);
                }
                $body = $this->searchPostData($article, 'body', $key);
                if (!empty($body)) {
                    $post->setBody($body);
                    $this->entityManager->persist($post);
                }
            }
        }
        $this->entityManager->flush();
    }

    /**
     * Function search specific array keys and return data
     * @param array $dataArray
     * @param string $search_value
     * @param string $key_to_search
     * @return mixed|void
     */
    public function searchPostData(array $dataArray, string $search_value, string $key_to_search)
    {
        if (is_array($dataArray[$key_to_search]) && array_key_exists($search_value, $dataArray[$key_to_search])) {

            return $dataArray[$key_to_search][$search_value];

        }

    }
}
