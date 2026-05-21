<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class UnsplashService
{
    public function __construct(private HttpClientInterface $client, private string $accessKey)
    {
    }

    public function getImageUrl(string $destination): ?string
    {
        $images = $this->getImageUrls($destination, 1);

        return $images[0] ?? null;
    }

    public function getImageUrls(string $destination, int $limit = 5): array
    {
        $response = $this->client->request('GET', 'https://api.unsplash.com/search/photos', [ // j'envoie une requette HTTP GET à l'API
            'headers' => [
                'Authorization' => 'Client-ID ' . $this->accessKey,
                'Accept-Version' => 'v1', 
            ],
            'query' => [
                'query' => $destination, // je passe la destination en paramètre de la requette
                'per_page' => $limit,
                'orientation' => 'landscape', // je demande des images de paysage
            ],
        ]);

        $data = $response->toArray();
        $images = [];

        foreach ($data['results'] ?? [] as $result) { // je parcours les résultats de la requette
            if (isset($result['urls']['regular'])) { // si l'image existe je l'ajoute au tableau des images
                $images[] = $result['urls']['regular']; 
            }
        }

        return $images; 
    }
}
