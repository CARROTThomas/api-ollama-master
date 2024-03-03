<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RandomMot
{
    public function __construct(private HttpClientInterface $httpClient){

    }
    public function random()
    {
        try {
            $response = $this->httpClient->request('GET', 'https://trouve-mot.fr/api/random');
            $tab = $response->toArray();

            if (!empty($tab) && isset($tab[0]["name"])) {
                return $tab[0]["name"];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }
}