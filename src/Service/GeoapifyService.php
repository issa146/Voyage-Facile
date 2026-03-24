<?php

namespace App\Service;


use symfony\contracts\HttpClient\HttpClientInterface;


class GeoapifyService {

private HttpClientInterface $client;
private string $apiKey;


public function __construct(HttpClientInterface $client, string $apiKey) {

    $this->client = $client;
    $this->apiKey =$apiKey;
    
}


public function search(string $city)  {
        $response = $this->client->request('GET', 'https://api.geoapify.com/v1/geocode/search', [ # j'envoie une requette GET à l'API
            'query' => [
                'text' => $city,
                'apiKey' => $this->apiKey,
            ],
        ]);

        $data = $response->toArray();

        if(empty($data['features'][0]['properties']['lat']) || empty($data['features'][0]['properties']['lon'])) {

            return null;
        }

        return [
            'lat' => $data['features'][0]['properties']['lat'],
            'lon' => $data['features'][0]['properties']['lon'],
    ];
    }    
}


?>