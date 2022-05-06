<?php

namespace App\Service;

class ArticleApiService
{
    private const API_URL = 'https://jsonplaceholder.typicode.com';
    private RestService $restService;

    public function __construct(RestService $restService)
    {
        $this->restService = $restService;
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
}
