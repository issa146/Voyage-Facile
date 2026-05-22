<?php

namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class AviationstackService
{
    private HttpClientInterface $httpClient;
    private string $apiKey;

    public function __construct(HttpClientInterface $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }

    public function searchFlight(string $departure, string $arrival, string $date): ?array
    {
        $response = $this->httpClient->request('GET', 'http://api.aviationstack.com/v1/flights', [
            'query' => [
                'access_key' => $this->apiKey, 
                'dep_iata' => $departure, // c'est le code IATA de l'aéroport de départ
                'arr_iata' => $arrival, // c'est le code IATA de l'aéroport d'arrivée
                'flight_date' => $date,
            ],
        ]);

        $data = $response->toArray();

        if (empty($data['data'])) {
            return null;
        }

        return $data['data'];
    }
}


?>