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
            $usersArr[$user['id']] = $user;
            foreach ($posts as $post) {
                if ($user['id'] === $post['userId']) {
                    $user[] = $post;
                    $usersArr[$user['id']] = $user;

                    $result = $usersArr;
                }
            }
        }
        return $result;
    }

    /**
     * Function add complete article array to database, or only author
     * @return void
     */
    public function addArticle()
    {
        $articles = $this->getArticle();
        if (!empty($articles)) {
            foreach ($articles as $article) {
                $author = new Author();
                $author->setName($article['name']);
                $this->entityManager->persist($author);

                foreach ($article as $key => $value) {
                    $title = $this->searchPostData($article, 'title', $key);
                    $body = $this->searchPostData($article, 'body', $key);

                    if (!empty($title) && !empty($body)) {
                        $post = new Post();
                        $post->setAuthor($author);
                        $post->setTitle($title);
                        $post->setBody($body);
                        $this->entityManager->persist($post);
                    }
                }
            }
            $this->entityManager->flush();
        }
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
