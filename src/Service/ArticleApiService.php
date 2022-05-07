<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;

class ArticleApiService
{
    private const API_URL = 'https://jsonplaceholder.typicode.com';
    private RestService $restService;
    private EntityManagerInterface $entityManager;

    public function __construct(RestService $restService, EntityManagerInterface $entityManager)
    {
        $this->restService = $restService;
        $this->entityManager = $entityManager;
    }

    /**
     * Function return complete merged  articles
     * @return array
     */
    public function getArticle(): array
    {
        $posts = $this->restService->fetchData('posts', self::API_URL);
        $users = $this->restService->fetchData('users', self::API_URL);

        return $this->mergeData($posts, $users);
    }

    /**
     * Function merge users and posts belonging to him into one array
     *
     * @param array $posts
     * @param array $users
     * @return array
     */
    public function mergeData(array $posts, array $users): array
    {
        $result = array();
        $usersArr = array();
        foreach ($users as $user) {
            foreach ($posts as $post) {
                if (isset($user) && $user['id'] === $post['userId']) {
                    $usersArr[$user['id']] = $user;
                    $user[] = $post;
                    $result = $usersArr;
                }
            }
        }
        return $result;
    }

    public function addArticle(): void
    {
        $articles = $this->getArticle();
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
