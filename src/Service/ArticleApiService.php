<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ArticleApiService
{
    private const API_URL = 'https://jsonplaceholder.typicode.com';
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getArticle()
    {
        $posts = $this->fetchData("posts");
        $users = $this->fetchData("users");

        $this->mergeData($posts, $users);

    }

    /**
     * Function merge users and posts belonging to him into one array
     *
     * @param array $posts
     * @param array $users
     * @return array
     */
    public function mergeData(array $posts, array $users):array
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

    /**
     * @param string $data
     * @return array
     */
    public function fetchData(string $data): array
    {
        $response = $this->client->request(
            'GET',
            self::API_URL . "/" . $data
        );

        return $response->toArray();
    }
}